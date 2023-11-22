<?php

namespace App\Services\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ValidationService

{

    public function validateDataProfile(array $data): array
    {
        $validator = Validation::createValidator();

        // Add validations for firstName, lastName, and email as needed

        $zipCodeErrors = $validator->validate($data['zipCode'], [
            new Assert\NotBlank(['message' => 'Le code postal ne doit pas être vide.']),
            new Assert\Regex([
                'pattern' => '/^\d{5}$/',
                'message' => 'Le code postal doit contenir 5 chiffres.',
            ]),
        ]);

        if (count($zipCodeErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($zipCodeErrors)];
        }

        $cityErrors = $validator->validate($data['city'], [
            new Assert\NotBlank(['message' => 'La ville ne doit pas être vide.']),
            new Assert\Regex([
                'pattern' => '/^[a-zA-Z]+$/',
                'message' => 'La ville ne doit contenir que des lettres.',
            ]),
        ]);

        if (count($cityErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($cityErrors)];
        }

        $dateOfBirthErrors = $validator->validate($data['dateOfBirth'], [
            new Assert\NotBlank(['message' => 'La date de naissance ne doit pas être vide.']),
            new Assert\Date(['message' => 'La date de naissance doit être une date valide.']),
        ]);

        if (count($dateOfBirthErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($dateOfBirthErrors)];
        }

        // Additional validations for other fields can be added here.

        return ['status' => true];
    }

    public function validateDataRegistration(array $data): array
    {
        $validator = Validation::createValidator();

        $firstNameErrors = $validator->validate($data['firstName'], [
            new Assert\NotBlank(['message' => 'Le nom ne doit pas être vide.']),
            new Assert\Length([
                'min' => 2,
                'max' => 255,
                'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
            ]),
            new Assert\Regex([
                'pattern' => '/\d/',
                'match' => false,
                'message' => 'Le nom ne doit pas contenir de chiffres.',
            ]),
        ]);
    
        if (count($firstNameErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($firstNameErrors)];
        }
    
        $lastNameErrors = $validator->validate($data['lastName'], [
            new Assert\NotBlank(['message' => 'Le prénom ne doit pas être vide.']),
            new Assert\Length([
                'min' => 2,
                'max' => 255,
                'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
            ]),
            new Assert\Regex([
                'pattern' => '/\d/',
                'match' => false,
                'message' => 'Le prénom ne doit pas contenir de chiffres.',
            ]),
        ]);
    
        if (count($lastNameErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($lastNameErrors)];
        }

        $emailErrors = $validator->validate($data['email'], [
            new Assert\NotBlank(['message' => "L'e-mail ne doit pas être vide."]),
            new Assert\Email(['message' => "L'e-mail n'est pas valide."]),
        ]);

        if (count($emailErrors) > 0) {
            return ['status' => false, 'message' => $this->formatValidationErrors($emailErrors)];
        }

        $passwordErrors = $this->validatePassword($data['password']);

        if ($passwordErrors) {
            return ['status' => false, 'message' => $passwordErrors];
        }

        return ['status' => true];
    }

    private function validatePassword(string $password): array
    {
        $validator = Validation::createValidator();
    
        $passwordErrors = $validator->validate($password, [
            new Assert\NotBlank(['message' => 'Le mot de passe ne doit pas être vide.']),
            new Assert\Length([
                'min' => 6,
                'max' => 255,
                'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
            ]),
            new Assert\Regex([
                'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            ]),
        ]);
    
        return $this->formatValidationErrors($passwordErrors);
    }

    private function formatValidationErrors($errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }

    
}
