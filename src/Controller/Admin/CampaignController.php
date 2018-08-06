<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\CampaignRepository;
use App\Repository\ClientRepository;
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
 * @Route("/campaigns")
 *
 * @Permission(menu="CAMPAIGN")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignController extends CrudController
{
    /**
     * @Route("/", methods={"GET"}, name="campaigns_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request, Paginator $paginator, ClientRepository $clientRepository)
    {
        $campaigns = $paginator->paginate(Campaign::class, Paginator::PER_PAGE, (int) $request->query->get('page', 1));

        $clients = [];
        $admin = false;

        /** @var User $user */
        $user = $this->getUser();
        if ($user->getGroup()->getName() === Str::make('Super Administrator')->uppercase()->__toString()) {
            $clients = $clientRepository->findAll();
            $admin = true;
        }

        if ($request->isXmlHttpRequest()) {
            $table = $this->renderView('campaign/table-content.html.twig', ['campaigns' => $campaigns, 'admin' => $admin]);
            $pagination = $this->renderView('campaign/pagination.html.twig', ['campaigns' => $campaigns]);

            return new JsonResponse([
                'table' => $table,
                'pagination' => $pagination,
            ]);
        }

        return $this->render('campaign/index.html.twig', ['title' => 'Program', 'campaigns' => $campaigns, 'clients' => $clients, 'admin' => $admin]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="campaigns_detail", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function find(string $id, CampaignRepository $repository, SerializerInterface $serializer)
    {
        $campaign = $repository->find($id);
        if (!$campaign) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($campaign, 'json', ['campaigns' => ['read']]));
    }

    /**
     * @Route("/save", methods={"POST"}, name="campaigns_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, CampaignRepository $repository, RequestHandler $requestHandler)
    {
        $primary = $request->get('id');
        if ($primary) {
            $campaign = $repository->find($primary);
        } else {
            $campaign = new Campaign();
        }

        $requestHandler->handle($request, $campaign);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($campaign);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/{id}/delete", methods={"POST"}, name="campaigns_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $id, CampaignRepository $repository)
    {
        if (!$campaign = $repository->find($id)) {
            return new NotFoundHttpException();
        }

        $this->remove($campaign);

        return new JsonResponse(['status' => 'OK']);
    }
}
