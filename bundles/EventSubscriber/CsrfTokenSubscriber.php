<?php

declare(strict_types=1);

namespace KejawenLab\Bima\EventSubscriber;

use KejawenLab\Bima\Request\RequestHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CsrfTokenSubscriber implements EventSubscriberInterface
{
    private $crsfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->crsfTokenManager = $csrfTokenManager;
    }

    public function validate(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {
            $csrfToken = $request->request->get('_csrf_token');
            if (!$this->crsfTokenManager->isTokenValid(new CsrfToken(RequestHandler::REQUEST_TOKEN_NAME, $csrfToken))) {
                throw new AccessDeniedException();
            }
        }
    }

    public function injectToken(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        if ($request->isMethod('POST') && $request->isXmlHttpRequest() && 'application/json' === $response->headers->get('Content-Type')) {
            $content = json_decode($response->getContent(), true);
            $content['_csrf_token'] = $this->crsfTokenManager->refreshToken(RequestHandler::REQUEST_TOKEN_NAME)->getValue();

            $event->setResponse(new JsonResponse($content));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['validate']],
            KernelEvents::RESPONSE => [['injectToken']],
        ];
    }
}
