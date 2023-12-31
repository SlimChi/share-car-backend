<?php

namespace App\Services;

use App\Dto\UserDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;




interface ProfileServiceInterface
{
    public function getProfile(): JsonResponse;

    public function addInfoProfile(array $data): JsonResponse;

    public function getUserImages(): JsonResponse;
    
    public function updatePassword(array $data): JsonResponse;
    
    public function addImageProfile(array $data): JsonResponse;

    public function disableAccount(): JsonResponse;

    public function addBiographyProfile(array $data): JsonResponse;
}