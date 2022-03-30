<?php

use function DI\create;

return [
  \Src\PlayerMatcher\Domain\Ports\PlayerRepository::class => create(\Src\PlayerMatcher\Adapters\FileRepository\PlayerRepository::class),
  \Src\PlayerMatcher\Adapters\Api\TokenRepository::class => create(\Src\PlayerMatcher\Adapters\FileRepository\TokenRepository::class),
];