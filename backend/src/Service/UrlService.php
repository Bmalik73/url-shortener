<?php

namespace App\Service;

use App\Dto\UrlInput;
use App\Dto\UrlOutput;
use App\Entity\Url;
use App\Exception\UrlNotFoundException;
use App\Repository\UrlRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UrlService
{
    private UrlRepository $urlRepository;
    private UrlEncoder $urlEncoder;
    private string $shortDomain;
    private int $defaultMaxAge;
    private int $minLength;

    public function __construct(
        UrlRepository $urlRepository,
        UrlEncoder $urlEncoder,
        ParameterBagInterface $params
    ) {
        $this->urlRepository = $urlRepository;
        $this->urlEncoder = $urlEncoder;
        $this->shortDomain = $params->get('url_shortener.domain');
        $this->defaultMaxAge = $params->get('url_shortener.max_age');
        $this->minLength = $params->get('url_shortener.min_length');
    }

    /**
     * Crée une URL raccourcie à partir d'une URL originale
     */
    public function createShortUrl(UrlInput $urlInput): UrlOutput
    {
        // Vérifier si l'URL existe déjà
        $existingUrl = $this->urlRepository->findByOriginalUrl($urlInput->getUrl());
        if ($existingUrl !== null && !$existingUrl->isExpired()) {
            return $this->createUrlOutput($existingUrl);
        }

        // Créer une nouvelle URL
        $url = new Url();
        $url->setOriginalUrl($urlInput->getUrl());

        // Définir la date d'expiration si nécessaire
        if ($urlInput->getExpiresInSeconds() !== null) {
            $expiresAt = (new \DateTimeImmutable())->modify('+' . $urlInput->getExpiresInSeconds() . ' seconds');
            $url->setExpiresAt($expiresAt);
        } elseif ($this->defaultMaxAge > 0) {
            $expiresAt = (new \DateTimeImmutable())->modify('+' . $this->defaultMaxAge . ' seconds');
            $url->setExpiresAt($expiresAt);
        }

        // Essayer de sauvegarder avec différents codes jusqu'à ce qu'il n'y ait pas de collision
        $maxRetries = 5;
        $retries = 0;
        $saved = false;

        while (!$saved && $retries < $maxRetries) {
            try {
                // Générer un code unique pour l'URL
                $code = $this->urlEncoder->generateRandomCode($this->minLength);
                $url->setCode($code);
                
                $this->urlRepository->save($url);
                $saved = true;
            } catch (UniqueConstraintViolationException $e) {
                $retries++;
                if ($retries >= $maxRetries) {
                    throw new \RuntimeException('Impossible de générer un code unique après plusieurs essais.', 0, $e);
                }
            }
        }

        return $this->createUrlOutput($url);
    }

    /**
     * Récupère l'URL originale à partir d'un code court
     */
    public function getOriginalUrl(string $code): string
    {
        $url = $this->urlRepository->findByCode($code);

        if (null === $url) {
            throw new UrlNotFoundException("URL avec le code '{$code}' non trouvée.");
        }

        if ($url->isExpired()) {
            throw new UrlNotFoundException("URL avec le code '{$code}' a expiré.");
        }

        // Incrémenter le compteur de visites
        $url->incrementVisitCount();
        $this->urlRepository->save($url);

        return $url->getOriginalUrl();
    }

    /**
     * Récupère les informations d'une URL à partir de son code
     */
    public function getUrlInfo(string $code): UrlOutput
    {
        $url = $this->urlRepository->findByCode($code);

        if (null === $url) {
            throw new UrlNotFoundException("URL avec le code '{$code}' non trouvée.");
        }

        return $this->createUrlOutput($url);
    }

    /**
     * Nettoie les URLs expirées
     */
    public function cleanupExpiredUrls(): int
    {
        return $this->urlRepository->deleteExpired();
    }

    /**
     * Crée un DTO de sortie à partir d'une entité URL
     */
    private function createUrlOutput(Url $url): UrlOutput
    {
        $output = new UrlOutput();
        $output->setOriginalUrl($url->getOriginalUrl());
        $output->setCode($url->getCode());
        $output->setShortUrl($this->buildShortUrl($url->getCode()));
        $output->setCreatedAt($url->getCreatedAt());
        $output->setExpiresAt($url->getExpiresAt());
        $output->setVisitCount($url->getVisitCount());

        return $output;
    }

    /**
     * Construit l'URL raccourcie complète
     */
    private function buildShortUrl(string $code): string
    {
        $scheme = 'http';
        if (str_starts_with($this->shortDomain, 'localhost') === false) {
            $scheme = 'https';
        }

        return "{$scheme}://{$this->shortDomain}/{$code}";
    }
}