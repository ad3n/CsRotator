<?php

declare(strict_types=1);

namespace KejawenLab\Bima\Controller;

use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\EntityEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class CrudController extends Controller
{
    protected function commit(object $entity)
    {
        $manager = $this->getDoctrine()->getManager();

        $this->get('event_dispatcher')->dispatch(BimaAdminEvents::PRE_COMMIT_EVENT, new EntityEvent($manager, $entity));

        $manager->persist($entity);
        $manager->flush();
    }

    protected function remove(object $entity)
    {
        $manager = $this->getDoctrine()->getManager();

        $this->get('event_dispatcher')->dispatch(BimaAdminEvents::PRE_COMMIT_EVENT, new EntityEvent($manager, $entity));

        $manager->remove($entity);
        $manager->flush();
    }
}
