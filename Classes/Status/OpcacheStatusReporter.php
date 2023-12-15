<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Status;

final class OpcacheStatusReporter
{
    public function getStatus(): array
    {
        return opcache_get_status(false);
    }
}
