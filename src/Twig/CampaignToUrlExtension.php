<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Campaign;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignToUrlExtension extends \Twig_Extension
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('campaign_to_url', [$this, 'generateUrl']),
        ];
    }

    public function generateUrl(Campaign $campaign): ? string
    {
        if (Campaign::CHAT === $campaign->getType()) {
            return $this->urlGenerator->generate('chat', ['slug' => $campaign->getSlug()]);
        }

        if (Campaign::FORM === $campaign->getType()) {
            return $this->urlGenerator->generate('lead', ['slug' => $campaign->getSlug()]);
        }

        if (Campaign::DIRECT === $campaign->getType()) {
            return $this->urlGenerator->generate('direct', ['slug' => $campaign->getSlug()]);
        }

        throw new NotFoundHttpException();
    }
}
