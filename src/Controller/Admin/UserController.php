<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Security\Permission\Permission;
use KejawenLab\Bima\Controller\CrudController;
use KejawenLab\Bima\Pagination\Paginator;
use KejawenLab\Bima\Request\RequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users")
 *
 * @Permission(menu="USER")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserController extends CrudController
{
    /**
     * @Route("/", methods={"GET"}, name="users_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, GroupRepository $groupRepository, ClientRepository $clientRepository)
    {
        $users = $paginator->paginate(User::class, Paginator::PER_PAGE, (int) $request->query->get('page', 1));

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('user/table-content.html.twig', ['users' => $users]);
            $pagination = $this->renderView('user/pagination.html.twig', ['users' => $users]);

            return new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('user/index.html.twig', ['title' => 'User', 'users' => $users, 'groups' => $groupRepository->findAll(), 'clients' => $clientRepository->findAll()]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="users_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, UserRepository $repository, SerializerInterface $serializer)
    {
        $user = $repository->find($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($user, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="users_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, UserRepository $repository, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $user = $repository->find($primary);
        } else {
            $user = new User();
        }

        $requestHandler->handle($request, $user);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($user);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="users_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, UserRepository $repository)
    {
        if (!$user = $repository->find($id)) {
            return new NotFoundHttpException();
        }

        $this->remove($user);

        return new JsonResponse(['status' => 'OK']);
    }
}
