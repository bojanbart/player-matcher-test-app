<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Controllers;

use Src\PlayerMatcher\Adapters\Api\GameApiService;

class GameEndpoint extends AbstractController
{
    public function __construct(private GameApiService $apiService)
    {
        parent::__construct();
    }

    public function create()
    {
        $playerId = $this->authorize();

        $parameters = $this->getParameters();

        $this->apiService->create($parameters['name'] ?? '', (int)$parameters['slots'], $playerId)
            ->send();
    }

    public function list()
    {
        $this->apiService->list()
            ->send();
    }

    public function get(int $id)
    {
        $this->apiService->get($id)
            ->send();
    }

    public function update(int $id)
    {

    }

    public function remove(int $id)
    {
        $playerId = $this->authorize();


    }
}