<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Campaign;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ClientRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use PHLAK\Twine\Str;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetClientWhenNotProvideSubscriber implements EventSubscriberInterface
{
    private $token;

    private $clientRepository;

    public function __construct(TokenStorageInterface $tokenStorage, ClientRepository $clientRepository)
    {
        $this->token = $tokenStorage->getToken();
        $this->clientRepository = $clientRepository;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        /** @var User $user */
        if (!($this->token && $user = $this->token->getUser())) {
            return;
        }

        if ($user->getGroup()->getName() !== Str::make('Super Administrator')->uppercase()->__toString()) {
            return;
        }

        $object = $event->getObject();
        if (!($object instanceof Campaign || $object instanceof Contact)) {
            return;
        }

        $object->setClient($this->clientRepository->find($user->getClient()->getId()));
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
