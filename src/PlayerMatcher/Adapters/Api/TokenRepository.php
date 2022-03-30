<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api;

interface TokenRepository
{

    public function storeToken(string $token, int $externalId): void;

    public function fetchByExternalId(int $externalId): string;
}