<?php

namespace App\Entity;

use App\Repository\SellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SellerRepository::class)]
class Seller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"{{ label }} filed is mandatory!")]
    #[ORM\Column(length: 25)]
    private ?string $location = null;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern:"/^[0][0-9]*$/", 
        message:"Phone number must be in format: 0631234567",
        )]
    #[Assert\Length(
        min: 9,
        max: 10,
        minMessage: '{{ label }} must be at least {{ limit }} digits long',
        maxMessage: '{{ label }} cannot be longer than {{ limit }} digits',
    )]
    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'seller', orphanRemoval: true)]
    private Collection $cars;

    /**
     * @var Collection<int, Communication>
     */
    #[ORM\OneToMany(targetEntity: Communication::class, mappedBy: 'seller', orphanRemoval: true)]
    private Collection $communications;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->communications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getlocation(): ?string
    {
        return $this->location;
    }

    public function setlocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setSeller($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getSeller() === $this) {
                $car->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Communication>
     */
    public function getCommunications(): Collection
    {
        return $this->communications;
    }

    public function addCommunication(Communication $communication): static
    {
        if (!$this->communications->contains($communication)) {
            $this->communications->add($communication);
            $communication->setSeller($this);
        }

        return $this;
    }

    public function removeCommunication(Communication $communication): static
    {
        if ($this->communications->removeElement($communication)) {
            // set the owning side to null (unless already changed)
            if ($communication->getSeller() === $this) {
                $communication->setSeller(null);
            }
        }

        return $this;
    }
}
