<?php

namespace App\Entity;

use App\Repository\TrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainRepository::class)
 */
class Train
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $route;

    /**
     * @ORM\Column(type="string")
     */
    private $trainType;

    /**
     * @ORM\OneToMany(targetEntity="Wagon", mappedBy="train")
     */
    private $wagons;

    /**
     * @ORM\OneToMany(targetEntity="Voyage", mappedBy="train")
     */
    private $voyages;

    /**
     * @ORM\OneToMany(targetEntity="Monitoring", mappedBy="train")
     */
    private $monitoringHistory;

    /**
     * @ORM\OneToMany(targetEntity="WayThrough", mappedBy="train")
     */
    private $wayThrough;

    public function __construct()
    {
        $this->wagons = new ArrayCollection();
        $this->voyages = new ArrayCollection();
        $this->monitoringHistory = new ArrayCollection();
        $this->stations = new ArrayCollection();
        $this->wayThrough = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getTrainType(): ?string
    {
        return $this->trainType;
    }

    public function setTrainType(string $trainType): self
    {
        $this->trainType = $trainType;

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
            $wagon->setTrain($this);
        }

        return $this;
    }

    public function removeWagon(Wagon $wagon): self
    {
        if ($this->wagons->contains($wagon)) {
            $this->wagons->removeElement($wagon);
            // set the owning side to null (unless already changed)
            if ($wagon->getTrain() === $this) {
                $wagon->setTrain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Voyage[]
     */
    public function getVoyages(): Collection
    {
        return $this->voyages;
    }

    public function addVoyage(Voyage $voyage): self
    {
        if (!$this->voyages->contains($voyage)) {
            $this->voyages[] = $voyage;
            $voyage->setTrain($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): self
    {
        if ($this->voyages->contains($voyage)) {
            $this->voyages->removeElement($voyage);
            // set the owning side to null (unless already changed)
            if ($voyage->getTrain() === $this) {
                $voyage->setTrain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Monitoring[]
     */
    public function getMonitoringHistory(): Collection
    {
        return $this->monitoringHistory;
    }

    public function addMonitoringHistory(Monitoring $monitoringHistory): self
    {
        if (!$this->monitoringHistory->contains($monitoringHistory)) {
            $this->monitoringHistory[] = $monitoringHistory;
            $monitoringHistory->setTrain($this);
        }

        return $this;
    }

    public function removeMonitoringHistory(Monitoring $monitoringHistory): self
    {
        if ($this->monitoringHistory->contains($monitoringHistory)) {
            $this->monitoringHistory->removeElement($monitoringHistory);
            // set the owning side to null (unless already changed)
            if ($monitoringHistory->getTrain() === $this) {
                $monitoringHistory->setTrain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Station[]
     */
    public function getStations(): Collection
    {
        return $this->stations;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->contains($station)) {
            $this->stations->removeElement($station);
        }

        return $this;
    }

    /**
     * @return Collection|WayThrough[]
     */
    public function getWayThrough(): Collection
    {
        return $this->wayThrough;
    }

    public function addWayThrough(WayThrough $wayThrough): self
    {
        if (!$this->wayThrough->contains($wayThrough)) {
            $this->wayThrough[] = $wayThrough;
            $wayThrough->setTrain($this);
        }

        return $this;
    }

    public function removeWayThrough(WayThrough $wayThrough): self
    {
        if ($this->wayThrough->contains($wayThrough)) {
            $this->wayThrough->removeElement($wayThrough);
            // set the owning side to null (unless already changed)
            if ($wayThrough->getTrain() === $this) {
                $wayThrough->setTrain(null);
            }
        }

        return $this;
    }

}
