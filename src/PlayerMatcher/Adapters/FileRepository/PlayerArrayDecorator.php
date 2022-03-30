<?php

namespace Src\PlayerMatcher\Adapters\FileRepository;

use Src\PlayerMatcher\Domain\Model\HumanPlayer;
use Src\PlayerMatcher\Domain\Model\Player;

class PlayerArrayDecorator implements Player
{

    public function __construct(private Player $player)
    {

    }

    public function toArray(): array
    {
        return array_merge($this->player->toArray(), ['id' => $this->player->getId()]);
    }

    public static function fromArray(array $raw): Player
    {
        return new HumanPlayer($raw['id'] ?? 0, $raw['name'] ?? '');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->player->getId();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->player->getName();
    }
}