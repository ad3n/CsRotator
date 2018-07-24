<?php

declare(strict_types=1);

namespace KejawenLab\Bima;

use KejawenLab\Bima\DependencyInjection\BimaAdminExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class BimaAdminBundle extends Bundle
{
    public function getContainerExtensionClass()
    {
        return BimaAdminExtension::class;
    }
}
