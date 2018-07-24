<?php

declare(strict_types=1);

namespace KejawenLab\Bima;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class BimaAdminEvents
{
    const REQUEST_EVENT = 'bima.admin.request';
    const PRE_VALIDATION_EVENT = 'bima.admin.pre_validation';
    const PAGINATION_EVENT = 'bima.admin.pagination';
    const PRE_COMMIT_EVENT = 'bima.admin.pre_commit';
}
