<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DestinationRepository $destinationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $destinationRepository->createQueryBuilder('d');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Current page number
            8 // Limit per page
        );

        return $this->render('destination/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/destination/{id}', name: 'destination_show')]
    public function show(DestinationRepository $destinationRepository, int $id): Response
    {
        $destination = $destinationRepository->find($id);

        if (!$destination) {
            throw $this->createNotFoundException('Destination not found');
        }

        return $this->render('destination/show.html.twig', [
            'destination' => $destination,
        ]);
    }
}
