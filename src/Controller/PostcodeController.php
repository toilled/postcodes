<?php

namespace App\Controller;

use App\Entity\Postcode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Postcode Controller
 */
class PostcodeController extends AbstractController
{
    /**
     * @Route("/search/code")
     */
    public function searchByCode(EntityManagerInterface $em, SerializerInterface $serializer, Request $request): Response
    {
        $searchTerm = str_replace(' ', '', $request->get('code')); // Postcodes are stored without spaces

        $postcodes = $em->getRepository(Postcode::class)
            ->createQueryBuilder('p')
            ->where('p.postcode LIKE :code')
            ->setParameter('code', "%$searchTerm%")
            ->getQuery()
            ->getResult();

        return new Response(
            $serializer->serialize($postcodes, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/search/nearby")
     */
    public function searchNearby(EntityManagerInterface $em, SerializerInterface $serializer, Request $request): Response
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        $postcodes = $em->getRepository(Postcode::class)
            ->createQueryBuilder('p')
            ->orderBy("abs(p.latitude - $latitude) + abs(p.longitude - $longitude)")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return new Response(
            $serializer->serialize($postcodes, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
