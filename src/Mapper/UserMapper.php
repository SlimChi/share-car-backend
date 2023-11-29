<?php

namespace App\Mapper;

use App\Dto\UserDto;
use App\Entity\User;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use App\Dto\UserChatDto;
use App\Entity\Chat;

class UserMapper
{
    private PropertyInfoExtractorInterface $propertyInfoExtractor;
    private $propertyAccessor;

    public function __construct()
    {

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();

        $this->propertyInfoExtractor = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor]
        );
    }

    public function convertUserDtoToEntity(UserDto $userDto, User $user): User
    {
        $this->copyProperties($userDto, $user);

        return $user;
    }

    public function convertEntityToUserDto(User $user): UserDto
    {
        $userDto = new UserDto();
        $this->copyProperties($user, $userDto);

        return $userDto;
    }

    public function convertArrayToUserDto(array $data): UserDto
    {
        $userDto = new UserDto();
        $this->copyProperties($data, $userDto);

        return $userDto;
    }

    private function copyProperties($source, $destination)
    {
        // Utilisez PropertyInfoExtractor seulement si l'objet source est un objet
        if (is_object($source)) {
            $properties = $this->propertyInfoExtractor->getProperties(get_class($source));
    
            foreach ($properties as $property) {
                $value = $this->propertyAccessor->getValue($source, $property);
    
                // Vérifiez si la propriété est de type DateTimeInterface et si la valeur est non nulle
                if ($this->isDateTimeProperty($destination, $property) && $value !== null) {
                    $this->propertyAccessor->setValue($destination, $property, new \DateTime($value));
                } elseif ($property === 'createdAt' && $value === null) {
                    // Ignorez la copie de la propriété "dateInscription" si la valeur est null
                    continue;
                } elseif ($property === 'creditCoin' && $value === null) {
                    // Définissez une valeur par défaut pour la propriété "creditJeton" si la valeur est null
                    $this->propertyAccessor->setValue($destination, $property, 0);
                } else {
                    $this->propertyAccessor->setValue($destination, $property, $value);
                }
            }
        } elseif (is_array($source)) {
            // Si l'objet source est un tableau, copiez simplement les valeurs correspondantes
            foreach ($source as $property => $value) {
                // Vérifiez si la propriété est de type DateTimeInterface et si la valeur est non nulle
                if ($this->isDateTimeProperty($destination, $property) && $value !== null) {
                    $this->propertyAccessor->setValue($destination, $property, new \DateTime($value));
                } elseif ($property === 'createdAt' && $value === null) {
                    // Ignorez la copie de la propriété "dateInscription" si la valeur est null
                    continue;
                } elseif ($property === 'creditCoin' && $value === null) {
                    // Définissez une valeur par défaut pour la propriété "creditJeton" si la valeur est null
                    $this->propertyAccessor->setValue($destination, $property, 0);
                } else {
                    $this->propertyAccessor->setValue($destination, $property, $value);
                }
            }
        }
    }
    
    
    private function isDateTimeProperty($object, $property)
    {
        $types = $this->propertyInfoExtractor->getTypes(get_class($object), $property);
    
        foreach ($types as $type) {
            if ($type->getClassName() === \DateTimeInterface::class) {
                return true;
            }
        }
    
        return false;
    }
    public function convertEntityToUserChatDto(Chat $chat): UserChatDto
    {
        return new UserChatDto(
            $chat->getId(),
            $chat->getMessage(),
            $chat->getRecipient()->getId(),
            $chat->getRecipient()->getUsername(),
            $chat->getCreatedAt()->format('Y-m-d H:i:s'),
            $chat->getSender()->getId(), 
            $chat->getCreatedAt()
        );
    }
    

    public function convertChatCollectionToUserChatDtoCollection(iterable $chats): array
    {
        $userChatDtoCollection = [];
        foreach ($chats as $chat) {
            $userChatDtoCollection[] = $this->convertEntityToUserChatDto($chat);
        }

        return $userChatDtoCollection;
    }
}
