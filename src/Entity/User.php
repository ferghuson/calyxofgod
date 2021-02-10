<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class User
{
    /**
     * @Assert\Email(message="Veuillez renseigner une adresse mail valide !")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Votre mot de passe !")
     */
    private $password;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
}
