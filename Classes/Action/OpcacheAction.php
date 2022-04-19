<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use TYPO3\CMS\Core\Type\Enumeration;

final class OpcacheAction extends Enumeration
{
    public const RESET = 'reset';
    public const STATUS = 'status';

    public function getRequestMethod(): string
    {
        return self::$requestMethods[$this->value];
    }

    public function getUriPath(): string
    {
        return self::$uriPaths[$this->value];
    }

    private static array $requestMethods = [
        self::RESET => 'post',
        self::STATUS => 'get',
    ];

    private static array $uriPaths = [
        self::RESET => '/-/opcache/reset',
        self::STATUS => '/-/opcache/status',
    ];
}
