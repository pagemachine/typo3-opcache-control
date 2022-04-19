<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Psr\Http\Client\ClientInterface;

final class OpcacheActionExecutor
{
    private ClientInterface $client;
    private OpcacheActionRequestFactory $opcacheActionRequestFactory;

    public function __construct(
        ClientInterface $client,
        OpcacheActionRequestFactory $opcacheActionRequestFactory
    ) {
        $this->client = $client;
        $this->opcacheActionRequestFactory = $opcacheActionRequestFactory;
    }

    public function execute(OpcacheAction $action): array
    {
        $request = $this->opcacheActionRequestFactory->createRequest($action);
        $response = $this->client->sendRequest($request);
        $result = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return $result;
    }
}
