<?php

namespace App\Mapper;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use App\Dto\TripDto;
use App\Entity\Trip;
use App\Entity\Step;

class TripMapper
{
    private PropertyInfoExtractorInterface $propertyInfo;

    public function __construct()
    {
        $this->propertyInfo = new PropertyInfoExtractor(
            [new ReflectionExtractor()],
            [new PhpDocExtractor()]
        );
    }

    public function mapDtoToEntity(TripDto $tripDto, Trip $trip): Trip
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->propertyInfo->getProperties(TripDto::class) as $property) {
            $value = $accessor->getValue($tripDto, $property);

            if ($property === 'steps') {
                // Handle array mapping separately
                $this->mapSteps($value, $trip);
            } elseif ($this->propertyInfo->getTypes(Trip::class, $property)) {
                $accessor->setValue($trip, $property, $value);
            }
        }

        return $trip;
    }

    public function mapEntityToDto(Trip $trip): TripDto
    {
        $tripDto = new TripDto();
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->propertyInfo->getProperties(TripDto::class) as $property) {
            if ($property === 'steps' && $this->propertyInfo->isReadable(Trip::class, $property)) {
                // Handle array mapping separately
                $this->mapStepsToDto($trip, $tripDto);
            } elseif ($this->propertyInfo->getTypes(TripDto::class, $property)) {
                $accessor->setValue($tripDto, $property, $accessor->getValue($trip, $property));
            }
        }

        return $tripDto;
    }

    public function mapSteps(array $stepsDto, Trip $trip): void
    {
        foreach ($stepsDto as $stepDto) {
            if (
                isset($stepDto['departure_address']) &&
                isset($stepDto['departure_zip_code']) &&
                isset($stepDto['departure_city']) &&
                isset($stepDto['arrival_address']) &&
                isset($stepDto['arrival_zip_code']) &&
                isset($stepDto['arrival_city'])
            ) {
                $step = new Step();
                $step->setDepartureAddress($stepDto['departure_address']);
                $step->setDepartureZipCode($stepDto['departure_zip_code']);
                $step->setDepartureCity($stepDto['departure_city']);
                $step->setArrivalAddress($stepDto['arrival_address']);
                $step->setArrivalZipCode($stepDto['arrival_zip_code']);
                $step->setArrivalCity($stepDto['arrival_city']);
    
                // Link the Etape to the Trip using the inverse side of the relationship
                $trip->addStep($step);
    
                // Log the values for debugging
                VarDumper::dump($stepDto);
            }
        }
    }
    
    

    private function mapStepsToDto($steps, TripDto $tripDto): void
    {
        $stepsDto = [];

        foreach ($steps as $step) {
            $stepsDto[] = [
                'id' => $step->getId(),
                'departure_address' => $step->getDepartureAddress(),
                'departure_zip_code' => $step->getDepartureZipCode(),
                'departure_city' => $step->getDepartureCity(),
                'arrival_address' => $step->getArrivalAddress(),
                'arrival_zip_code' => $step->getArrivalZipCode(),
                'arrival_city' => $step->getArrivalCity(),
            ];

            // Log the values for debugging
            VarDumper::dump($stepsDto);
        }

        $tripDto->setEtapes($stepsDto);
    }
    
}
