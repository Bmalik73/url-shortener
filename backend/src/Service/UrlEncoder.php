<?php

namespace App\Service;

class UrlEncoder
{
    private const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private const BASE = 62; // Longueur de l'alphabet

    private int $minLength;

    public function __construct(int $minLength = 6)
    {
        $this->minLength = $minLength;
    }

    /**
     * Encode un ID numérique en une chaîne courte unique
     */
    public function encode(int $id): string
    {
        $code = '';
        $num = $id;

        // Conversion de base-10 à base-62
        while ($num > 0) {
            $code = self::ALPHABET[$num % self::BASE] . $code;
            $num = intdiv($num, self::BASE);
        }

        // Assurer une longueur minimale
        while (strlen($code) < $this->minLength) {
            $code = self::ALPHABET[0] . $code;
        }

        return $code;
    }

    /**
     * Decode une chaîne courte en ID numérique
     */
    public function decode(string $code): int
    {
        $num = 0;
        $length = strlen($code);

        for ($i = 0; $i < $length; $i++) {
            $position = strpos(self::ALPHABET, $code[$i]);
            if ($position === false) {
                throw new \InvalidArgumentException("Code contient des caractères invalides: {$code}");
            }
            $num = $num * self::BASE + $position;
        }

        return $num;
    }

    /**
     * Génère un code aléatoire unique
     */
    public function generateRandomCode(int $length = 6): string
    {
        $code = '';
        $alphabetLength = strlen(self::ALPHABET);

        for ($i = 0; $i < $length; $i++) {
            $code .= self::ALPHABET[random_int(0, $alphabetLength - 1)];
        }

        return $code;
    }
}