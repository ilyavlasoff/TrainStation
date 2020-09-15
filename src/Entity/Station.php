<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="WayThrough", mappedBy="station")
     */
    private $paths;

    public function __construct()
    {
        $this->voyages = new ArrayCollection();
        $this->trains = new ArrayCollection();
        $this->paths = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Train[]
     */
    public function getTrains(): Collection
    {
        return $this->trains;
    }

    public function addTrain(Train $train): self
    {
        if (!$this->trains->contains($train)) {
            $this->trains[] = $train;
            $train->addStation($this);
        }

        return $this;
    }

    public function removeTrain(Train $train): self
    {
        if ($this->trains->contains($train)) {
            $this->trains->removeElement($train);
            $train->removeStation($this);
        }

        return $this;
    }

    /**
     * @return Collection|WayThrough[]
     */
    public function getPaths(): Collection
    {
        return $this->paths;
    }

    public function addPath(WayThrough $path): self
    {
        if (!$this->paths->contains($path)) {
            $this->paths[] = $path;
            $path->setStation($this);
        }

        return $this;
    }

    public function removePath(WayThrough $path): self
    {
        if ($this->paths->contains($path)) {
            $this->paths->removeElement($path);
            // set the owning side to null (unless already changed)
            if ($path->getStation() === $this) {
                $path->setStation(null);
            }
        }

        return $this;
    }



}
