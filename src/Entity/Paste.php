<?php

namespace App\Entity;

use App\Repository\PasteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasteRepository::class)]
class Paste
{
    #[ORM\Id]
    #[ORM\Column]
    private string $uuid;


    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private int $access = 1;

    #[ORM\Column(type: 'datetime',  options: ['default' => NULL])]
    private \DateTimeInterface $expired;

    #[ORM\Column(length: 1000)]
    private ?string $content = null;

    #[ORM\Column(length: 10)]
    private ?string $lang = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $createdAt = null;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setId(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->content;
    }

    public function setAccess(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getExpired(): ?string
    {
        return $this->content;
    }

    public function setExpired(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->content;
    }

    public function setLang(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
