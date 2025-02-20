<?php

declare(strict_types=1);

namespace Pagemachine\OpcacheControl\Http;

use Pagemachine\OpcacheControl\Http\Exception\InvalidSignatureException;
use Pagemachine\OpcacheControl\Http\Exception\MissingSignatureException;
use Psr\Http\Message\RequestInterface;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;

final class RequestSignature
{
    private const HEADER_NAME = 'X-Opcache-Control-Signature';

    public function __construct(private readonly HashService $hashService) {}

    public function sign(RequestInterface $request): RequestInterface
    {
        $token = GeneralUtility::makeInstance(Random::class)
            ->generateRandomHexString(64);
        $signature = $this->hashService->appendHmac($token);

        return $request->withHeader(self::HEADER_NAME, $signature);
    }

    public function verify(RequestInterface $request): void
    {
        $signature = $request->getHeaderLine(self::HEADER_NAME);

        if (empty($signature)) {
            throw new MissingSignatureException('Missing request signature', 1649952064);
        }

        try {
            $this->hashService->validateAndStripHmac($signature);
        } catch (InvalidHashException $e) {
            throw new InvalidSignatureException('Invalid request signature', 1649952115, $e);
        }
    }
}
