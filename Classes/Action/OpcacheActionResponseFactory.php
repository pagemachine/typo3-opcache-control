<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Action;

use Pagemachine\OpcacheControl\Http\Exception\InvalidSignatureException;
use Pagemachine\OpcacheControl\Http\Exception\MissingSignatureException;
use Pagemachine\OpcacheControl\Http\RequestSignature;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class OpcacheActionResponseFactory
{
    public function __construct(
        private readonly RequestSignature $requestSignature,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function createResponse(ServerRequestInterface $request, \Closure $payload = null): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader('content-type', 'application/json');

        try {
            $this->requestSignature->verify($request);
        } catch (MissingSignatureException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]));

            return $response->withStatus(401);
        } catch (InvalidSignatureException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]));

            return $response->withStatus(403);
        }

        $result = [
            'success' => true,
        ];

        if ($payload !== null) {
            $result = $payload($result);
        }

        $response->getBody()->write(json_encode($result));

        return $response;
    }
}
