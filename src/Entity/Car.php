<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    public const ALLOWED_BRANDS = ['Alfa Romeo', 'Fiat', 'Ford', 'Peugeot', 'Renault',];
    public const ALLOWED_BODY_TYPES = ['Convertible', 'Hatchback', 'Minivan', 'Sedan', 'SUV',]; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Car brand filed is mandatory!")]
    #[Assert\Choice(choices:Car::ALLOWED_BRANDS, message:"{{ value }} is not valid brand!")]
    private ?string $brand = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Model filed is mandatory!")]
    private ?string $model = null;

    #[ORM\Column(length: 11)]
    #[Assert\NotBlank(message:"Body type filed is mandatory!")]
    #[Assert\Choice(choices:Car::ALLOWED_BODY_TYPES, message:"Allowed body types are: Convertible, Hatchback, Minivan, Sedan and SUV")]
    private ?string $bodyType = null;

    #[ORM\ManyToOne(cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Engine $engine = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $registrationExpirationDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\Url]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Assert\NotBlank(message:"Must enter address of the Ad!")]
    #[Assert\Url(relativeProtocol: true, message:"Ad addresss must start with https//: or http//: !")]
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[Assert\NotBlank(message:"Must enter the car price!")]
    #[Assert\GreaterThan(value:499, message:"Minimal price must be at least 500 euros!")]
    #[ORM\Column]
    private ?int $price = null;

    
    #[Assert\Type(type: Seller::class)]
    #[Assert\Valid]
    #[ORM\ManyToOne(inversedBy: 'cars', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seller $seller = null;

    /**
     * @var Collection<int, Communication>
     */
    #[ORM\OneToMany(targetEntity: Communication::class, mappedBy: 'car', orphanRemoval: true)]
    private Collection $communication;

    #[ORM\ManyToOne(inversedBy: 'car')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->communication = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getBodyType(): ?string
    {
        return $this->bodyType;
    }

    public function setBodyType(string $bodyType): static
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    public function getEngine(): ?Engine
    {
        return $this->engine;
    }

    public function setEngine(?Engine $engine): static
    {
        $this->engine = $engine;

        return $this;
    }

    public function getRegistrationExpirationDate(): ?\DateTimeImmutable
    {
        return $this->registrationExpirationDate;
    }

    public function setRegistrationExpirationDate(?\DateTimeImmutable $registrationExpirationDate): static
    {
        $this->registrationExpirationDate = $registrationExpirationDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return Collection<int, Communication>
     */
    public function getCommunication(): Collection
    {
        return $this->communication;
    }

    public function addCommunication(Communication $communication): static
    {
        if (!$this->communication->contains($communication)) {
            $this->communication->add($communication);
            $communication->setCar($this);
        }

        return $this;
    }

    public function removeCommunication(Communication $communication): static
    {
        if ($this->communication->removeElement($communication)) {
            // set the owning side to null (unless already changed)
            if ($communication->getCar() === $this) {
                $communication->setCar(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
