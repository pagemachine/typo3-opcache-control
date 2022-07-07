<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

final class OpcacheActionExecutor implements LoggerAwareInterface
{
    private const MAX_ATTEMPTS = 3;

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

        $attempt = 1;

        do {
            sleep($attempt - 1);

            $this->logger->debug('Sending Opcache action request', [
                'uri' => (string)$request->getUri(),
                'method' => $request->getMethod(),
                'attempt' => $attempt,
                'maxAttempts' => self::MAX_ATTEMPTS,
            ]);

            $response = $this->client->sendRequest($request);

            $this->logger->debug('Received Opcache action response', [
                'uri' => (string)$request->getUri(),
                'status' => $response->getStatusCode(),
                'attempt' => $attempt,
                'maxAttempts' => self::MAX_ATTEMPTS,
            ]);

            try {
                $result = json_decode(
                    (string)$response->getBody(),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $this->logger->debug('Parsed Opcache action JSON response', [
                    'uri' => (string)$request->getUri(),
                    'result' => $result,
                    'attempt' => $attempt,
                    'maxAttempts' => self::MAX_ATTEMPTS,
                ]);

                break;
            } catch (\JsonException $e) {
                $this->logger->error('Failed parsing Opcache action JSON response', [
                    'uri' => (string)$request->getUri(),
                    'contentType' => $response->getHeaderLine('content-type'),
                    'body' => (string)$response->getBody(),
                    'attempt' => $attempt,
                    'maxAttempts' => self::MAX_ATTEMPTS,
                ]);

                $result = [
                    'success' => false,
                    'error' => sprintf('Invalid JSON response (%s), see log for details', $e->getMessage()),
                ];
            }
        } while (self::MAX_ATTEMPTS > $attempt++);

        return $result;
    }
}
