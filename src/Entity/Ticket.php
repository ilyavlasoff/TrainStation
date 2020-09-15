<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TicketStatus", inversedBy="tickets")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="Station")
     * @ORM\JoinColumn(name="source_station", referencedColumnName="id")
     */
    private $sourceStation;

    /**
     * @ORM\ManyToOne(targetEntity="Station")
     * @ORM\JoinColumn(name="destination_station", referencedColumnName="id")
     */
    private $destinationStation;

    /**
     * @ORM\Column(type="float")
     */
    private $routeLength;

    /**
     * @ORM\Column(type="decimal")
     */
    private $priceForKm;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Voyage", inversedBy="tickets")
     * @ORM\JoinColumn(name="voyage_id", referencedColumnName="id")
     */
    private $voyage;

    /**
     * @ORM\ManyToOne(targetEntity="Wagon", inversedBy="tickets")
     * @ORM\JoinColumn(name="wagon_id", referencedColumnName="id")
     */
    private $wagon;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getRouteLength(): ?float
    {
        return $this->routeLength;
    }

    public function setRouteLength(float $routeLength): self
    {
        $this->routeLength = $routeLength;

        return $this;
    }

    public function getPriceForKm(): ?string
    {
        return $this->priceForKm;
    }

    public function setPriceForKm(string $priceForKm): self
    {
        $this->priceForKm = $priceForKm;

        return $this;
    }

    public function getStatus(): ?TicketStatus
    {
        return $this->status;
    }

    public function setStatus(?TicketStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSourceStation(): ?Station
    {
        return $this->sourceStation;
    }

    public function setSourceStation(?Station $sourceStation): self
    {
        $this->sourceStation = $sourceStation;

        return $this;
    }

    public function getDestinationStation(): ?Station
    {
        return $this->destinationStation;
    }

    public function setDestinationStation(?Station $destinationStation): self
    {
        $this->destinationStation = $destinationStation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?Voyage $voyage): self
    {
        $this->voyage = $voyage;

        return $this;
    }

    public function getWagon(): ?Wagon
    {
        return $this->wagon;
    }

    public function setWagon(?Wagon $wagon): self
    {
        $this->wagon = $wagon;

        return $this;
    }




}
