<?php

declare(strict_types=1);

namespace KejawenLab\Bima\Twig;

use KejawenLab\Bima\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginatorExtension extends \Twig_Extension
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('start_page_number', [$this, 'startPageNumber']),
        ];
    }

    public function startPageNumber()
    {
        return Paginator::PER_PAGE * ((int) $this->request->query->get('page', 1) - 1) + 1;
    }
}
