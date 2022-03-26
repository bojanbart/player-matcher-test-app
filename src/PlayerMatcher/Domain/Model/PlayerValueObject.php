<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

class PlayerValueObject
{
    public function __construct(private string $name)
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}