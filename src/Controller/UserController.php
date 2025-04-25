<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/users', name: 'api_users_')]
final class UserController extends AbstractController{
    #[Route('', name: 'create',methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(!$data || !isset($data['names'],$data['lastnames'])) {
            return new JsonResponse(['error' => 'Incomplete data.'], Response::HTTP_BAD_REQUEST);
        }
        $user = new User();
        $user->setName($data['names']);
        $user->setLastName($data['lastnames']);

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'message' => ['User created successfully.'],
            'user' => ['id' => $user->getId(),
            'names' => $user->getName(),
            'lastnames' => $user->getLastName()]
        ], Response::HTTP_CREATED);
    }
}
