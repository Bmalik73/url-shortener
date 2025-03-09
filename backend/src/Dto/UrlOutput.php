<?php

namespace App\Dto;

class UrlOutput
{
    private string $originalUrl;
    private string $shortUrl;
    private string $code;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $expiresAt = null;
    private int $visitCount;

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;
        return $this;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;
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

    public function getVisitCount(): int
    {
        return $this->visitCount;
    }

    public function setVisitCount(int $visitCount): self
    {
        $this->visitCount = $visitCount;
        return $this;
    }
}