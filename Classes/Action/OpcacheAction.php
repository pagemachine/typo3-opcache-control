<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use TYPO3\CMS\Core\Type\Enumeration;

final class OpcacheAction extends Enumeration
{
    public const RESET = 'opcache_reset';
    public const STATUS = 'opcache_status';

    public function getRequestMethod(): string
    {
        return self::$requestMethods[$this->value];
    }

    public function getUriQuery(): string
    {
        return sprintf('eID=%s', $this->value);
    }

    private static array $requestMethods = [
        self::RESET => 'post',
        self::STATUS => 'get',
    ];
}
