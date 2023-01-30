<?php

declare(strict_types = 1);

namespace Pagemachine\OpcacheControl\Action;

use Pagemachine\OpcacheControl\Http\RequestSignature;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use TYPO3\CMS\Core\Site\SiteFinder;

final class OpcacheActionRequestFactory
{
    private RequestFactoryInterface $requestFactory;
    private RequestSignature $requestSignature;
    private SiteFinder $siteFinder;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        RequestSignature $requestSignature,
        SiteFinder $siteFinder
    ) {
        $this->requestFactory = $requestFactory;
        $this->requestSignature = $requestSignature;
        $this->siteFinder = $siteFinder;
    }

    /**
     * @throws NoSiteException if no site was found
     */
    public function createRequest(OpcacheAction $action): RequestInterface
    {
        foreach ($this->siteFinder->getAllSites() as $site) {
            $uri = $site->getRouter()->generateUri((string)$site->getRootPageId());
            $uri = $uri->withPath($uri->getPath() . $action->getUriPath());

            $request = $this->requestFactory->createRequest($action->getRequestMethod(), $uri);
            $request = $this->requestSignature->sign($request);

            return $request;
        }

        throw new NoSiteException('No site found', 1650357924);
    }
}
