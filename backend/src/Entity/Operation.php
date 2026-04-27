<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\OperationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    securityPostDenormalize: "object.getUser() == user",
    operations: [
        new Patch(security: "object.getUser() == user"),
        new Delete(security: "object.getUser() == user"),
    ]
)]
#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le label est obligatoire')]
    private ?string $label = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\Positive(message: 'Le montant doit être positif')]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: "operations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: "operations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function setCategory(?Category $category): static
    {
        $this->category = $category;
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
