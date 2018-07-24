<?php

declare(strict_types=1);

namespace KejawenLab\Bima\Annotation;

/**
 * @Annotation()
 * @Target("CLASS")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Crud
{
    private $searchable = [];

    public function __construct(array $configs = [])
    {
        if (isset($configs['searchable']) && \is_array($configs['searchable'])) {
            $this->searchable = $configs['searchable'];
        }
    }

    public function getSearchableFields()
    {
        return $this->searchable;
    }
}
