<?php

declare(strict_types=1);

class SimpleCipher
{
    public string $key;
    public function __construct(string $key = null)
    {
        if ($key !== null && !ctype_lower( $key)) {
            throw new InvalidArgumentException("The key is invalid.");
        }
        $this->key = $key ?? $this->generateKey();
    }

    public function encode(string $text): string
    {
        return implode("",
            array_map(fn($index, $letter) => chr((ord($letter) + ord($this->key[$index]) - 2 * ord("a")) % 26 + ord("a")),
                array_keys(str_split($text)),
                str_split($text)
            )
        );
    }

    public function decode(string $secret): string
    {
        return implode("",
            array_map(fn($index, $letter) => chr((ord($letter) - ord($this->key[$index]) + 26) % 26 + ord("a")),
                array_keys(str_split($secret)),
                str_split($secret)
            )
        );

    }

    private function generateKey(): string
    {
        return implode("", array_map(fn() => chr(rand(ord("a"), ord("z"))), range(1, 100)));
    }
}
