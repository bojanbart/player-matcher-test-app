<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

interface Player extends Opponent
{
    public function getId(): int;

    public function getName(): string;
}