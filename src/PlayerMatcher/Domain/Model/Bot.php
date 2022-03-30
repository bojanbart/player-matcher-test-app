<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Domain\Model;

use Src\PlayerMatcher\Domain\Exceptions\BotInvalidLevelException;

class Bot implements Opponent
{
    const STRONG_BOT_LEVEL   = 'strong';
    const MEDIOCRE_BOT_LEVEL = 'mediocre';
    const WEAK_BOT_LEVEL     = 'weak';

    public function __construct(protected string $level)
    {
        if (!in_array($this->level, [self::STRONG_BOT_LEVEL, self::MEDIOCRE_BOT_LEVEL, self::WEAK_BOT_LEVEL]))
        {
            throw new BotInvalidLevelException("Unknown bot level: {$this->level}");
        }
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    public static function week(): Bot
    {
        return new Bot(self::WEAK_BOT_LEVEL);
    }

    public static function mediocre(): Bot
    {
        return new Bot(self::MEDIOCRE_BOT_LEVEL);
    }

    public static function strong(): Bot
    {
        return new Bot(self::STRONG_BOT_LEVEL);
    }

    public function toArray()
    {
        return [
            'name' => "AI BOT {$this->level}",
        ];
    }
}
