<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Scalar\String_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="usr")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var Bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $twoFactorAuthentication;

    /**
     * @var String
     * @ORM\Column(type="string", nullable=true)
     */
    private $twoFactorCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $passportData;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="user")
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="Benefits", mappedBy="user")
     */
    private $benefits;

    /**
     * @ORM\OneToOne(targetEntity="Bonuses", mappedBy="user")
     */
    private $bonuses;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->benefits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTwoFactorAuthentication(): ?bool
    {
        return $this->twoFactorAuthentication;
    }

    public function setTwoFactorAuthentication(bool $twoFactorAuthentication): self
    {
        $this->twoFactorAuthentication = $twoFactorAuthentication;

        return $this;
    }

    public function getTwoFactorCode(): ?string
    {
        return $this->twoFactorCode;
    }

    public function setTwoFactorCode(?string $twoFactorCode): self
    {
        $this->twoFactorCode = $twoFactorCode;

        return $this;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPassportData(): ?string
    {
        return $this->passportData;
    }

    public function setPassportData(string $passportData): self
    {
        $this->passportData = $passportData;

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
            $ticket->setUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getUser() === $this) {
                $ticket->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Benefits[]
     */
    public function getBenefits(): Collection
    {
        return $this->benefits;
    }

    public function addBenefit(Benefits $benefit): self
    {
        if (!$this->benefits->contains($benefit)) {
            $this->benefits[] = $benefit;
            $benefit->setUser($this);
        }

        return $this;
    }

    public function removeBenefit(Benefits $benefit): self
    {
        if ($this->benefits->contains($benefit)) {
            $this->benefits->removeElement($benefit);
            // set the owning side to null (unless already changed)
            if ($benefit->getUser() === $this) {
                $benefit->setUser(null);
            }
        }

        return $this;
    }

    public function getBonuses(): ?Bonuses
    {
        return $this->bonuses;
    }

    public function setBonuses(?Bonuses $bonuses): self
    {
        $this->bonuses = $bonuses;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $bonuses ? null : $this;
        if ($bonuses->getUser() !== $newUser) {
            $bonuses->setUser($newUser);
        }

        return $this;
    }
}
