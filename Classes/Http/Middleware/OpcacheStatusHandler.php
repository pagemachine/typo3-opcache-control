<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Http\Middleware;

use Pagemachine\OpcacheControl\Action\OpcacheAction;
use Pagemachine\OpcacheControl\Action\OpcacheActionResponseFactory;
use Pagemachine\OpcacheControl\Status\OpcacheStatusReporter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OpcacheStatusHandler implements MiddlewareInterface
{
    private OpcacheActionResponseFactory $opcacheActionResponseFactory;
    private OpcacheStatusReporter $opcacheStatusReporter;

    public function __construct(
        OpcacheActionResponseFactory $opcacheActionResponseFactory,
        OpcacheStatusReporter $opcacheStatusReporter
    ) {
        $this->opcacheActionResponseFactory = $opcacheActionResponseFactory;
        $this->opcacheStatusReporter = $opcacheStatusReporter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = new OpcacheAction(OpcacheAction::STATUS);

        if (strtolower($request->getMethod()) !== $action->getRequestMethod() || $request->getUri()->getPath() !== $action->getUriPath()) {
            return $handler->handle($request);
        }

        $response = $this->opcacheActionResponseFactory->createResponse($request, function (array $result): array {
            $result['status'] = $this->opcacheStatusReporter->getStatus();

            return $result;
        });

        return $response;
    }
}
