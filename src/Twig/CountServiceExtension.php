<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Contact;
use App\Repository\CampaignContactRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CountServiceExtension extends \Twig_Extension
{
    private $campaignContactRepository;

    public function __construct(CampaignContactRepository $campaignContactRepository)
    {
        $this->campaignContactRepository = $campaignContactRepository;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('count_services', [$this, 'countService']),
        ];
    }

    public function countService(Contact $contact): int
    {
        if ($total = $this->campaignContactRepository->countService($contact)) {
            return $total;
        }

        return 0;
    }
}
