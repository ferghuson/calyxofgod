<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Votre nom !")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Votre adresse mail !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre numéro est invalide !")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Votre message !")
     */
    private $message;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reply;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sentAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $replyAt;

    /**
     * @ORM\PrePersist
     */
    public function generateSentAt()
    {
        date_default_timezone_set('Africa/Lome');
        $this->sentAt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function generateReplyAt()
    {
        date_default_timezone_set('Africa/Lome');
        $this->replyAt = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist
     */
    public function cleanPhoneNumber()
    {
        $this->phone = str_replace('-', '', str_replace(' ', '', $this->phone));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getReplyAt(): ?\DateTimeInterface
    {
        return $this->replyAt;
    }

    public function setReplyAt(?\DateTimeInterface $replyAt): self
    {
        $this->replyAt = $replyAt;

        return $this;
    }
}
