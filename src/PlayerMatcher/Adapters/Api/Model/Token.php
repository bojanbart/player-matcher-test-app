<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Model;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    const SECRET_KEY = 'safrt44s';
    const LIFETIME   = 12; // in hours

    private bool  $decoded = false;
    private bool  $valid;
    private array $payload;

    public function __construct(private string $token)
    {

    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    public static function createNew(array $payload): Token
    {
        $reqFields = [
            'iat'     => time(),
            'exp'     => self::getExpirationTime(),
            'payload' => $payload,
        ];

        return new Token(JWT::encode($reqFields, self::SECRET_KEY, 'HS256'));
    }

    private static function getExpirationTime(): int
    {
        return time() + 60 * 60 + self::LIFETIME;
    }

    public function isValid(): bool
    {
        if (!$this->decoded)
        {
            $this->decode();
        }

        return $this->valid;
    }

    public function getPayload(): array
    {
        if (!$this->decoded)
        {
            $this->decode();
        }

        return $this->payload;
    }

    private function decode(): void
    {
        try
        {
            $decoded = JWT::decode($this->token, new Key(self::SECRET_KEY, 'HS256'));
        }
        catch (\Exception $e)
        {
            $this->payload = [];
            $this->valid   = false;
            $this->decoded = true;

            return;
        }

        $this->payload = $decoded['payload'] ?? [];
        $this->valid   = true;
        $this->decoded = true;
    }
}