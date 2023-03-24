<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Habitats::class, mappedBy: 'categories')]
    private Collection $habitats;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Habitats::class)]
    private Collection $habitations;

    public function __construct()
    {
        $this->habitats = new ArrayCollection();
        $this->habitations = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Habitats>
     */
    public function getHabitats(): Collection
    {
        return $this->habitats;
    }

    public function addHabitat(Habitats $habitat): self
    {
        if (!$this->habitats->contains($habitat)) {
            $this->habitats->add($habitat);
            $habitat->addCategory($this);
        }

        return $this;
    }

    public function removeHabitat(Habitats $habitat): self
    {
        if ($this->habitats->removeElement($habitat)) {
            $habitat->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Habitats>
     */
    public function getHabitations(): Collection
    {
        return $this->habitations;
    }

    public function addHabitation(Habitats $habitation): self
    {
        if (!$this->habitations->contains($habitation)) {
            $this->habitations->add($habitation);
            $habitation->setCategorie($this);
        }

        return $this;
    }

    public function removeHabitation(Habitats $habitation): self
    {
        if ($this->habitations->removeElement($habitation)) {
            // set the owning side to null (unless already changed)
            if ($habitation->getCategorie() === $this) {
                $habitation->setCategorie(null);
            }
        }

        return $this;
    }
}
