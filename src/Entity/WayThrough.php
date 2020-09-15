<?php

namespace App\Entity;

use App\Repository\WayThroughRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WayThroughRepository::class)
 */
class WayThrough
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Train", inversedBy="wayThrough")
     */
    private $train;

    /**
     * @ORM\ManyToOne(targetEntity="Station", inversedBy="paths")
     */
    private $station;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }
}
