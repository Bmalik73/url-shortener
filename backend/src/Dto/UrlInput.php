<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UrlInput
{
    #[Assert\NotBlank(message: "L'URL est obligatoire")]
    #[Assert\Url(message: "L'URL n'est pas valide")]
    #[Assert\Length(max: 2048, maxMessage: "L'URL ne peut pas dépasser {{ limit }} caractères")]
    private string $url;

    #[Assert\Type(type: 'integer', message: "La durée de validité doit être un nombre entier")]
    #[Assert\PositiveOrZero(message: "La durée de validité doit être positive ou zéro")]
    private ?int $expiresInSeconds = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getExpiresInSeconds(): ?int
    {
        return $this->expiresInSeconds;
    }

    public function setExpiresInSeconds(?int $expiresInSeconds): self
    {
        $this->expiresInSeconds = $expiresInSeconds;
        return $this;
    }
}