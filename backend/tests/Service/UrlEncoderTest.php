<?php

namespace App\Tests\Service;

use App\Service\UrlEncoder;
use PHPUnit\Framework\TestCase;

class UrlEncoderTest extends TestCase
{
    private UrlEncoder $urlEncoder;
    
    protected function setUp(): void
    {
        $this->urlEncoder = new UrlEncoder(6);
    }
    
    public function testEncode(): void
    {
        $this->assertIsString($this->urlEncoder->encode(123));
        $this->assertGreaterThanOrEqual(6, strlen($this->urlEncoder->encode(123)));
    }
    
    public function testDecode(): void
    {
        $code = $this->urlEncoder->encode(123);
        $this->assertEquals(123, $this->urlEncoder->decode($code));
    }
    
    public function testGenerateRandomCode(): void
    {
        $code = $this->urlEncoder->generateRandomCode();
        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
        
        $code = $this->urlEncoder->generateRandomCode(8);
        $this->assertEquals(8, strlen($code));
    }
    
    public function testEncodeDecodePair(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $code = $this->urlEncoder->encode($i);
            $decoded = $this->urlEncoder->decode($code);
            $this->assertEquals($i, $decoded, "Failed encoding/decoding for number: $i");
        }
    }
    
    public function testMinimumLength(): void
    {
        // Les petits nombres devraient toujours produire un code de longueur minimale
        $code = $this->urlEncoder->encode(5);
        $this->assertGreaterThanOrEqual(6, strlen($code));
        
        // Créer un encodeur avec une longueur minimale différente
        $longEncoder = new UrlEncoder(10);
        $code = $longEncoder->encode(5);
        $this->assertGreaterThanOrEqual(10, strlen($code));
    }

    public function testGenerateRandomCodeUniqueness(): void
    {
        $codes = [];
        $iterations = 100;
        
        for ($i = 0; $i < $iterations; $i++) {
            $codes[] = $this->urlEncoder->generateRandomCode();
        }
        
        // Vérifier l'unicité (il devrait y avoir autant d'éléments uniques que d'itérations)
        $this->assertEquals($iterations, count(array_unique($codes)));
    }
}