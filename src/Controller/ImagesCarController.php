<?php

namespace App\Controller;

use App\Entity\ImageCars;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ImagesCarController extends AbstractController
{
    private $manager;
    private $user;

    public function __construct(EntityManagerInterface $manager, UserRepository $user, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
         $this->manager = $manager;
         $this->user = $user;
         $this->jwtManager = $jwtManager;
         $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/images_car', name: 'app_images_car', methods: ['GET'])]
    public function imagesCar(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage, UserRepository $user): Response
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }

        return $this->json($user);
    }

    #[Route('/api/useraddurlcars', name: 'image_car_urls', methods: ['POST'])]
    public function addImageCar(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();
    
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }
    
        $data = json_decode($request->getContent(), true);
    
        if (isset($data['image_car_urls']) && is_array($data['image_car_urls'])) {
            $imageUrls = $data['image_car_urls'];
    
            foreach ($imageUrls as $imageUrl) {
                $image = new ImageCars();
                $image->setImageUrl($imageUrl);
                $image->setUser($user);
    
                $entityManager->persist($image);
            }
    
            $entityManager->flush();
    
            return new JsonResponse(['message' => 'Images ajoutées avec succès.']);
        } else {
            return new JsonResponse(['message' => 'Les URLs des images sont manquantes ou au mauvais format.'], 400);
        }
    }
    
    
    #[Route('/api/get_cars_images', name: 'cars_images', methods: ['GET'])]
    public function getUserImages(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();
    
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User non authentifié.'], 401);
        }
    
        $images = $user->getImagesCars();
        $imageData = [];
    
        foreach ($images as $image) {
            $imageData[] = [
                'id' => $image->getId(),
                'url' => $image->getImageUrl(), 
            ];
        }
    
        return new JsonResponse(['imagesCar' => $imageData]);
    }

    #[Route('/api/delete_car_image/{id}', name: 'delete_car_image', methods: ['DELETE'])]
    public function deleteCarImage(Request $request, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $image = $entityManager->getRepository(ImageCars::class)->find($id);
        if (!$image) {
            return new JsonResponse(['message' => 'Image non trouvée.'], 404);
        }
    
  
        $entityManager->remove($image);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Image supprimée avec succès.']);
    }
    
}
