<?php

namespace App\Entity;

use App\Repository\EngineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EngineRepository::class)]
class Engine
{
    public const ALLOWED_FUEL_TYPES = ['Petrol', 'Petrol LPG', 'Diesel', 'Electric'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Choice(choices:Engine::ALLOWED_FUEL_TYPES)]
    #[ORM\Column(length: 20)]
    private ?string $fuelType = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 4,
        minMessage: '{{ label }} must be at least {{ limit }} digits long',
        maxMessage: '{{ label }} cannot be longer than {{ limit }} digits',
    )]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $engineDisplacement = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 3,
        minMessage: '{{ label }} must be at least {{ limit }} digits long',
        maxMessage: '{{ label }} cannot be longer than {{ limit }} digits',
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $powerKW = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 3,
        minMessage: '{{ label }} must be at least {{ limit }} digits long',
        maxMessage: '{{ label }} cannot be longer than {{ limit }} digits',
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $powerHP = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(string $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getEngineDisplacement(): ?int
    {
        return $this->engineDisplacement;
    }

    public function setEngineDisplacement(int $engineDisplacement): static
    {
        $this->engineDisplacement = $engineDisplacement;

        return $this;
    }

    public function getPowerKW(): ?int
    {
        return $this->powerKW;
    }

    public function setPowerKW(int $powerKW): static
    {
        $this->powerKW = $powerKW;

        return $this;
    }

    public function getPowerHP(): ?int
    {
        return $this->powerHP;
    }

    public function setPowerHP(int $powerHP): static
    {
        $this->powerHP = $powerHP;

        return $this;
    }
}
