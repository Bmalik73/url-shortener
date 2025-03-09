<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UrlControllerTest extends WebTestCase
{
    public function testCreateShortUrl(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/urls',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['url' => 'https://example.com'])
        );
        
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('originalUrl', $responseData);
        $this->assertArrayHasKey('shortUrl', $responseData);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertEquals('https://example.com', $responseData['originalUrl']);
    }
    
    public function testCreateShortUrlValidationError(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/urls',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['url' => 'invalid-url'])
        );
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('url', $responseData['errors']);
    }
    
    public function testGetUrlInfo(): void
    {
        // D'abord créer une URL
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/urls',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['url' => 'https://example.org'])
        );
        
        $createResponse = json_decode($client->getResponse()->getContent(), true);
        $code = $createResponse['code'];
        
        // Ensuite récupérer les infos de l'URL
        $client->request('GET', '/api/urls/' . $code);
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertEquals('https://example.org', $responseData['originalUrl']);
        $this->assertEquals($code, $responseData['code']);
    }
    
    public function testLookupUrl(): void
    {
        // D'abord créer une URL
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/urls',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['url' => 'https://example.net'])
        );
        
        $createResponse = json_decode($client->getResponse()->getContent(), true);
        $code = $createResponse['code'];
        
        // Ensuite faire une recherche avec le code
        $client->request(
            'POST',
            '/api/lookup',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['code' => $code])
        );
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertEquals('https://example.net', $responseData['originalUrl']);
    }
}