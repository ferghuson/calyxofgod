<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"}, message = "Cette catégorie existe déjà !")
 */
class ProductCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrez le nom de la catégorie !")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCollection", mappedBy="category")
     */
    private $collections;

    public function __construct()
    {
        $this->collections = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function treatName()
    {
        $this->name = strtolower(trim($this->name));
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function generateSlug()
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|ProductCollection[]
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    public function addCollection(ProductCollection $collection): self
    {
        if (!$this->collections->contains($collection)) {
            $this->collections[] = $collection;
            $collection->setCategory($this);
        }

        return $this;
    }

    public function removeCollection(ProductCollection $collection): self
    {
        if ($this->collections->contains($collection)) {
            $this->collections->removeElement($collection);
            // set the owning side to null (unless already changed)
            if ($collection->getCategory() === $this) {
                $collection->setCategory(null);
            }
        }

        return $this;
    }
}
