<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

class HumanPlayer implements Player
{
    public function __construct(protected int $id, protected string $name)
    {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
        ];
    }
}