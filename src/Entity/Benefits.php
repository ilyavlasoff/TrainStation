<?php

namespace App\Entity;

use App\Repository\BenefitsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BenefitsRepository::class)
 */
class Benefits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="benefits")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $validBefore;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $validDocs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValidBefore(): ?\DateTimeInterface
    {
        return $this->validBefore;
    }

    public function setValidBefore(\DateTimeInterface $validBefore): self
    {
        $this->validBefore = $validBefore;

        return $this;
    }

    public function getValidDocs(): ?string
    {
        return $this->validDocs;
    }

    public function setValidDocs(string $validDocs): self
    {
        $this->validDocs = $validDocs;

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

}
