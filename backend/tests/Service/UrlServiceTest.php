<?php

namespace App\Tests\Service;

use App\Dto\UrlInput;
use App\Entity\Url;
use App\Exception\UrlNotFoundException;
use App\Repository\UrlRepository;
use App\Service\UrlEncoder;
use App\Service\UrlService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UrlServiceTest extends TestCase
{
    private UrlRepository $urlRepository;
    private UrlEncoder $urlEncoder;
    private ParameterBagInterface $parameterBag;
    private UrlService $urlService;
    
    protected function setUp(): void
    {
        $this->urlRepository = $this->createMock(UrlRepository::class);
        $this->urlEncoder = $this->createMock(UrlEncoder::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        
        $this->parameterBag->method('get')
            ->willReturnMap([
                ['url_shortener.domain', 'localhost:8000'],
                ['url_shortener.max_age', 31536000],
                ['url_shortener.min_length', 6],
            ]);
        
        $this->urlService = new UrlService(
            $this->urlRepository,
            $this->urlEncoder,
            $this->parameterBag
        );
    }
    
    public function testCreateShortUrl(): void
    {
        // Préparation des données de test
        $urlInput = new UrlInput();
        $urlInput->setUrl('https://example.com');
        
        $url = new Url();
        $url->setOriginalUrl('https://example.com');
        $url->setCode('abc123');
        $url->setCreatedAt(new \DateTimeImmutable());
        
        // Configuration des mocks
        $this->urlRepository->expects($this->once())
            ->method('findByOriginalUrl')
            ->with('https://example.com')
            ->willReturn(null);
            
        $this->urlEncoder->expects($this->once())
            ->method('generateRandomCode')
            ->willReturn('abc123');
            
        $this->urlRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function($savedUrl) {
                return $savedUrl->getOriginalUrl() === 'https://example.com'
                    && $savedUrl->getCode() === 'abc123';
            }))
            ->willReturnCallback(function(Url $url) {
                // Simuler l'ajout d'un ID
                $reflectionClass = new \ReflectionClass(Url::class);
                $reflectionProperty = $reflectionClass->getProperty('id');
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($url, 1);
                return $url;
            });
        
        // Exécution du test
        $result = $this->urlService->createShortUrl($urlInput);
        
        // Assertions
        $this->assertEquals('https://example.com', $result->getOriginalUrl());
        $this->assertEquals('abc123', $result->getCode());
        $this->assertEquals('http://localhost:8000/abc123', $result->getShortUrl());
    }
    
    public function testGetOriginalUrlThrowsExceptionWhenNotFound(): void
    {
        $this->urlRepository->expects($this->once())
            ->method('findByCode')
            ->with('notfound')
            ->willReturn(null);
            
        $this->expectException(UrlNotFoundException::class);
        $this->urlService->getOriginalUrl('notfound');
    }
}