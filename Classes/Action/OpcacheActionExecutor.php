<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

final class OpcacheActionExecutor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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

        $this->logger->debug('Sending Opcache action request', [
            'uri' => (string)$request->getUri(),
            'method' => $request->getMethod(),
        ]);

        $response = $this->client->sendRequest($request);

        $this->logger->debug('Received Opcache action response', [
            'uri' => (string)$request->getUri(),
            'status' => $response->getStatusCode(),
            'contentType' => $response->getHeaderLine('content-type'),
        ]);

        try {
            $result = json_decode(
                (string)$response->getBody(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            $result = [
                'success' => false,
                'error' => sprintf('Invalid JSON response (%s)', $e->getMessage()),
            ];
        }

        return $result;
    }
}
