<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="Cet utilisateur existe déjà !")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Votre prénom !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Votre nom !")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Adresse mail invalide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Numéro invalide !")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Au moins 8 caractères !")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="customer")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="customer")
     */
    private $commands;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PasswordRecover", mappedBy="customer")
     */
    private $passwordRecovers;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function generateSlug()
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->firstName.' '.$this->lastName);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function cleanPhoneNumber()
    {
        $this->phone = str_replace('-', '', str_replace(' ', '', $this->phone));
    }

    /**
     * @ORM\PrePersist
     */
    public function generateRegisteredAt()
    {
        date_default_timezone_set('Africa/Lome');
        $this->registeredAt = new \DateTime('now');
    }

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->commands = new ArrayCollection();
        $this->passwordRecovers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getFullName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setCustomer($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getCustomer() === $this) {
                $address->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Command[]
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setCustomer($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->contains($command)) {
            $this->commands->removeElement($command);
            // set the owning side to null (unless already changed)
            if ($command->getCustomer() === $this) {
                $command->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PasswordRecover[]
     */
    public function getPasswordRecovers(): Collection
    {
        return $this->passwordRecovers;
    }

    public function addPasswordRecover(PasswordRecover $passwordRecover): self
    {
        if (!$this->passwordRecovers->contains($passwordRecover)) {
            $this->passwordRecovers[] = $passwordRecover;
            $passwordRecover->setCustomer($this);
        }

        return $this;
    }

    public function removePasswordRecover(PasswordRecover $passwordRecover): self
    {
        if ($this->passwordRecovers->contains($passwordRecover)) {
            $this->passwordRecovers->removeElement($passwordRecover);
            // set the owning side to null (unless already changed)
            if ($passwordRecover->getCustomer() === $this) {
                $passwordRecover->setCustomer(null);
            }
        }

        return $this;
    }
}
