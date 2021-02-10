<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Payment
{
    private $method;

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
