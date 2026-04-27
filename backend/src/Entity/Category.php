<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    securityPostDenormalize: "object.getUser() == user",
    operations: [
        new Patch(security: "object.getUser() == user"),
        new Delete(security: "object.getUser() == user"),
    ]
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Assert\Unique(fields: ['title', 'user'], message: 'Cette catégorie existe déjà pour cet utilisateur')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(min: 1, max: 255, message: 'Le titre doit faire entre 1 et 255 caractères')]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: "categories")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /** @var Collection<int, Operation> */
    #[ORM\OneToMany(targetEntity: Operation::class, mappedBy: "category")]
    private Collection $operations;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): static
    {
        $this->title = $title;
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

    /** @return Collection<int, Operation> */
    public function getOperations(): Collection
    {
        return $this->operations;
    }
}
