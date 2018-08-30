<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Repository\ClientRepository;
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
 * @Route("/clients")
 *
 * @Permission(menu="GROUP")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ClientController extends CrudController
{
    /**
     * @Route("/", methods={"GET"}, name="clients_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator)
    {
        $clients = $paginator->paginate(Client::class, Paginator::PER_PAGE, (int) $request->query->get('page', 1));

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('client/table-content.html.twig', ['clients' => $clients]);
            $pagination = $this->renderView('client/pagination.html.twig', ['clients' => $clients]);

            return new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('client/index.html.twig', ['title' => 'Klien', 'clients' => $clients]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="clients_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, ClientRepository $repository, SerializerInterface $serializer)
    {
        $client = $repository->find($id);
        if (!$client) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($client, 'json', ['groups' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="clients_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, ClientRepository $repository, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $client = $repository->find($primary);
        } else {
            $client = new Client();
        }

        $requestHandler->handle($request, $client);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($client);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="clients_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, ClientRepository $repository)
    {
        if (!$client = $repository->find($id)) {
            return new NotFoundHttpException();
        }

        $this->remove($client);

        return new JsonResponse(['status' => 'OK']);
    }
}
