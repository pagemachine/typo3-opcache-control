<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Http\Middleware;

use Pagemachine\OpcacheControl\Action\OpcacheAction;
use Pagemachine\OpcacheControl\Action\OpcacheActionResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Service\OpcodeCacheService;

final class OpcacheResetHandler implements MiddlewareInterface
{
    public function __construct(
        private readonly OpcacheActionResponseFactory $opcacheActionResponseFactory,
        private readonly OpcodeCacheService $opcodeCacheService,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = new OpcacheAction(OpcacheAction::RESET);

        if (strtolower($request->getMethod()) !== $action->getRequestMethod() || $request->getUri()->getPath() !== $action->getUriPath()) {
            return $handler->handle($request);
        }

        $response = $this->opcacheActionResponseFactory->createResponse($request);

        $this->opcodeCacheService->clearAllActive();

        return $response;
    }
}
