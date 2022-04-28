<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Pagemachine\OpcacheControl\Action\OpcacheActionResponseFactory;
use Pagemachine\OpcacheControl\Status\OpcacheStatusReporter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class OpcacheStatusAction
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

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->opcacheActionResponseFactory->createResponse($request, function (array $result): array {
            $result['status'] = $this->opcacheStatusReporter->getStatus();

            return $result;
        });

        return $response;
    }
}
