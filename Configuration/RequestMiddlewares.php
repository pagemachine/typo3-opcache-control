<?php

declare(strict_types=1);

return [
    'frontend' => [
        'pagemachine/opcache-control/reset' => [
            'target' => \Pagemachine\OpcacheControl\Http\Middleware\OpcacheResetHandler::class,
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
                'typo3/cms-frontend/static-route-resolver',
            ],
        ],
        'pagemachine/opcache-control/status' => [
            'target' => \Pagemachine\OpcacheControl\Http\Middleware\OpcacheStatusHandler::class,
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
                'typo3/cms-frontend/static-route-resolver',
            ],
        ],
    ],
];
