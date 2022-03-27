<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

class GameValueObject
{

    public function __construct(private string $name, private int $slots)
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSlots(): int
    {
        return $this->slots;
    }
}