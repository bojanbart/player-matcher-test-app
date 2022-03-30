<?php

declare(strict_types=1);

namespace Src\PlayerMatcher\Adapters\Api\Controllers;

use Src\PlayerMatcher\Adapters\Api\PlayerApiService;

class PlayerEndpoint extends AbstractController
{
    public function __construct(private PlayerApiService $apiService)
    {
        parent::__construct();

    }

    public function create()
    {
        $parameters = json_decode($this->request->getContent(), true);

        $this->apiService->create($parameters['name'] ?? '')
            ->send();
    }

    public function get(int $id)
    {
        $this->apiService->get($id)
            ->send();
    }
}