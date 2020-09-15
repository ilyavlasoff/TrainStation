<?php

namespace App\Entity;

use App\Repository\WagonTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WagonTypeRepository::class)
 */
class WagonType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $typeDescription;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="Wagon", mappedBy="type")
     */
    private $wagons;

    public function __construct()
    {
        $this->wagons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDescription(): ?string
    {
        return $this->typeDescription;
    }

    public function setTypeDescription(string $typeDescription): self
    {
        $this->typeDescription = $typeDescription;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Wagon[]
     */
    public function getWagons(): Collection
    {
        return $this->wagons;
    }

    public function addWagon(Wagon $wagon): self
    {
        if (!$this->wagons->contains($wagon)) {
            $this->wagons[] = $wagon;
            $wagon->setType($this);
        }

        return $this;
    }

    public function removeWagon(Wagon $wagon): self
    {
        if ($this->wagons->contains($wagon)) {
            $this->wagons->removeElement($wagon);
            // set the owning side to null (unless already changed)
            if ($wagon->getType() === $this) {
                $wagon->setType(null);
            }
        }

        return $this;
    }

}
