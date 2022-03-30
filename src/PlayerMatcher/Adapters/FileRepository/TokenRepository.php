<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\FileRepository;

use Src\PlayerMatcher\Adapters\Api\TokenRepository as TokenRepositoryInterface;

class TokenRepository implements TokenRepositoryInterface
{
    const FILENAME = 'tokens.json';

    private array $tokens = [];
    private bool  $loaded = false;

    public function storeToken(string $token, int $externalId): void
    {
        $this->loadIfNecessary();

        $this->tokens[$externalId] = $token;

        $this->save();
    }

    public function fetchByExternalId(int $externalId): string
    {
        $this->loadIfNecessary();

        return $this->tokens[$externalId] ?? "invalid token";
    }

    private function loadIfNecessary(): void
    {
        if (!$this->loaded)
        {
            $this->load();
            $this->loaded = true;
        }
    }

    private function save(): void
    {
        file_put_contents(__DIR__ . "/../../../../data/" . self::FILENAME, json_encode($this->tokens), LOCK_EX);
    }

    private function load(): void
    {
        $file = __DIR__ . "/../../../../data/" . self::FILENAME;

        if (!file_exists($file))
        {
            return;
        }

        $this->tokens = json_decode(file_get_contents($file), true);
    }
}