<?php
namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface CarServiceInterface
{
    public function addCar(User $user, Request $request): array;

    public function getCars(User $user): array;

    public function getMyCars(User $user): array;
}