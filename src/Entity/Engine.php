<?php

namespace App\Entity;

use App\Repository\EngineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EngineRepository::class)]
class Engine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $fuelType = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?int $engineDisplacement = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $powerKW = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $powerHP = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getfuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setfuelType(string $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getengineDisplacement(): ?int
    {
        return $this->engineDisplacement;
    }

    public function setengineDisplacement(int $engineDisplacement): static
    {
        $this->engineDisplacement = $engineDisplacement;

        return $this;
    }

    public function getpowerKW(): ?int
    {
        return $this->powerKW;
    }

    public function setpowerKW(int $powerKW): static
    {
        $this->powerKW = $powerKW;

        return $this;
    }

    public function getpowerHP(): ?int
    {
        return $this->powerHP;
    }

    public function setpowerHP(int $powerHP): static
    {
        $this->powerHP = $powerHP;

        return $this;
    }
}
