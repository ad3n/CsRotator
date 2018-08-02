<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use App\Security\Permission\Permission;
use KejawenLab\Bima\Controller\CrudController;
use KejawenLab\Bima\Pagination\Paginator;
use KejawenLab\Bima\Request\RequestHandler;
use PHLAK\Twine\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/contacts")
 *
 * @Permission(menu="CONTACT")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContactController extends CrudController
{
    /**
     * @Route("/", methods={"GET"}, name="contacts_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, ClientRepository $clientRepository)
    {
        $contacts = $paginator->paginate(Contact::class, Paginator::PER_PAGE, (int) $request->query->get('page', 1));

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('contact/table-content.html.twig', ['contacts' => $contacts]);
            $pagination = $this->renderView('contact/pagination.html.twig', ['contacts' => $contacts]);

            return new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
            ]);
        }

        $clients = [];
        $admin = false;

        /** @var User $user */
        $user = $this->getUser();
        if ($user->getGroup()->getName() === Str::make('Super Administrator')->uppercase()->__toString()) {
            $clients = $clientRepository->findAll();
            $admin = true;
        }

        return $this->render('contact/index.html.twig', ['title' => 'Kontak', 'contacts' => $contacts, 'clients' => $clients, 'admin' => $admin]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="contacts_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, ContactRepository $repository, SerializerInterface $serializer)
    {
        $contact = $repository->find($id);
        if (!$contact) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($contact, 'json', ['contacts' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="contacts_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, ContactRepository $repository, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $contact = $repository->find($primary);
        } else {
            $contact = new Contact();
        }

        $requestHandler->handle($request, $contact);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($contact);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="contacts_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, ContactRepository $repository)
    {
        if (!$contact = $repository->find($id)) {
            return new NotFoundHttpException();
        }

        $this->remove($contact);

        return new JsonResponse(['status' => 'OK']);
    }
}
