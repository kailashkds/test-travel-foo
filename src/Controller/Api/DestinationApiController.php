<?php

// src/Controller/Api/DestinationController.php
namespace App\Controller\Api;

use App\Repository\DestinationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DestinationApiController extends AbstractController
{

    public function __construct(
        private DestinationRepository $destinationRepository,
        private PaginatorInterface $paginator,
        private SerializerInterface $serializer
    )
    {

    }

    #[Route('/api/destinations', name: 'api_destinations')]
    public function getDestinations(Request $request): Response
    {
        $name = $request->query->get('name');
        $id = $request->query->get('id');

        $qb = $this->destinationRepository->createQueryBuilder('d');

        if ($name) {
            $qb->andWhere('d.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if ($id) {
            $qb->andWhere('d.id LIKE :id')
                ->setParameter('id', '%' . $id . '%');
        }

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1), // Current page number
            10 // Limit per page
        );

        $data = $this->serializer->normalize($pagination->getItems(), null, ['groups' => 'destination:list']);

        return $this->json([
            'data' => $data,
            'pagination' => [
                'total' => $pagination->getTotalItemCount(),
                'count' => count($pagination->getItems()),
                'current_page' => $pagination->getCurrentPageNumber(),
                'per_page' => $pagination->getItemNumberPerPage(),
            ]
        ]);
    }
}
