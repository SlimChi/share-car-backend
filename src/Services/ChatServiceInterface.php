<?php

namespace App\Services;


use Symfony\Component\HttpFoundation\JsonResponse;




interface ChatServiceInterface
{
    public function getAllUsersByUsername(): JsonResponse;

}    