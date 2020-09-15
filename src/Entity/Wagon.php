<?php

namespace App\Entity;

use App\Repository\WagonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WagonRepository::class)
 */
class Wagon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Train", inversedBy="wagons")
     * @ORM\JoinColumn(name="train_id", referencedColumnName="id")
     */
    private $train;

    /**
     * @ORM\ManyToOne(targetEntity="WagonType", inversedBy="wagons")
     * @ORM\JoinColumn(name="wagon_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $placesCount;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="wagon")
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlacesCount(): ?int
    {
        return $this->placesCount;
    }

    public function setPlacesCount(int $placesCount): self
    {
        $this->placesCount = $placesCount;

        return $this;
    }

    public function getTrain(): ?Train
    {
        return $this->train;
    }

    public function setTrain(?Train $train): self
    {
        $this->train = $train;

        return $this;
    }

    public function getType(): ?WagonType
    {
        return $this->type;
    }

    public function setType(?WagonType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setWagon($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getWagon() === $this) {
                $ticket->setWagon(null);
            }
        }

        return $this;
    }


}
