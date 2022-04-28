<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Pagemachine\OpcacheControl\Action\OpcacheActionResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Service\OpcodeCacheService;

final class OpcacheResetAction
{
    private OpcacheActionResponseFactory $opcacheActionResponseFactory;
    private OpcodeCacheService $opcodeCacheService;

    public function __construct(
        OpcacheActionResponseFactory $opcacheActionResponseFactory,
        OpcodeCacheService $opcodeCacheService
    ) {
        $this->opcodeCacheService = $opcodeCacheService;
        $this->opcacheActionResponseFactory = $opcacheActionResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $method = strtolower($request->getMethod());
        $response = $this->opcacheActionResponseFactory->createResponse($request, function (array $result) use ($method): array {
            if ($method !== 'post') {
                throw new \BadMethodCallException(sprintf('Invalid request method "%s", expected "post"', $method), 1651143693);
            }

            return $result;
        });

        $this->opcodeCacheService->clearAllActive();

        return $response;
    }
}
