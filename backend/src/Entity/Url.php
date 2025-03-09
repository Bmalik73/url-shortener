<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
#[ORM\Table(name: 'urls')]
#[ORM\Index(columns: ['code'], name: 'idx_url_code')]
#[ORM\UniqueConstraint(name: 'unique_url_code', columns: ['code'])]
#[ORM\HasLifecycleCallbacks]
class Url
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 2048)]
    #[Assert\NotBlank]
    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    private string $originalUrl;

    #[ORM\Column(type: 'string', length: 16, unique: true)]
    private string $code;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $visitCount = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lastVisitedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function isExpired(): bool
    {
        if (null === $this->expiresAt) {
            return false;
        }
        return $this->expiresAt < new \DateTimeImmutable();
    }

    public function getVisitCount(): int
    {
        return $this->visitCount;
    }

    public function incrementVisitCount(): self
    {
        $this->visitCount++;
        $this->lastVisitedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getLastVisitedAt(): ?\DateTimeImmutable
    {
        return $this->lastVisitedAt;
    }

    public function setLastVisitedAt(?\DateTimeImmutable $lastVisitedAt): self
    {
        $this->lastVisitedAt = $lastVisitedAt;
        return $this;
    }
}