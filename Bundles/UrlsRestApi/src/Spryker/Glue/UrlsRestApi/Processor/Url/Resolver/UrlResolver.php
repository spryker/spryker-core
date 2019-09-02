<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Url\Resolver;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface;

class UrlResolver implements UrlResolverInterface
{
    protected const URL_REQUEST_PARAMETER = 'url';

    /**
     * @var \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var \Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface
     */
    protected $urlResponseBuilder;

    /**
     * @var \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface[]
     */
    protected $restUrlResolverAttributesTransferProviderPlugins;

    /**
     * @param \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface $urlResponseBuilder
     * @param \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\RestUrlResolverAttributesTransferProviderPluginInterface[] $restUrlResolverAttributesTransferProviderPlugins
     */
    public function __construct(
        UrlsRestApiToUrlStorageClientInterface $urlStorageClient,
        UrlResponseBuilderInterface $urlResponseBuilder,
        array $restUrlResolverAttributesTransferProviderPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->urlResponseBuilder = $urlResponseBuilder;
        $this->restUrlResolverAttributesTransferProviderPlugins = $restUrlResolverAttributesTransferProviderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getUrlResolver(RestRequestInterface $restRequest): RestResponseInterface
    {
        $urlRequestParameter = urldecode($restRequest->getHttpRequest()->query->get(static::URL_REQUEST_PARAMETER));

        if (!$urlRequestParameter) {
            return $this->urlResponseBuilder->createUrlRequestParamMissingErrorResponse();
        }

        $urlStorageTransfer = $this->getUrlStorageTransfer($urlRequestParameter);

        if (!$urlStorageTransfer) {
            return $this->urlResponseBuilder->createUrlNotFoundErrorResponse();
        }

        $restUrlResolverAttributesTransfer = $this->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);
        if (!$restUrlResolverAttributesTransfer) {
            return $this->urlResponseBuilder->createUrlNotFoundErrorResponse();
        }

        return $this->urlResponseBuilder->createUrlResolverResourceResponse($restUrlResolverAttributesTransfer);
    }

    /**
     * @param string $urlRequestParameter
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    protected function getUrlStorageTransfer(string $urlRequestParameter): ?UrlStorageTransfer
    {
        $urlStorageTransfer = $this->urlStorageClient->findUrlStorageTransferByUrl($urlRequestParameter);

        if (!$urlStorageTransfer) {
            return null;
        }

        if (!$urlStorageTransfer->getFkResourceRedirect()) {
            return $urlStorageTransfer;
        }

        $urlRedirectStorageTransfer = $this->urlStorageClient->findUrlRedirectStorageById(
            $urlStorageTransfer->getFkResourceRedirect()
        );

        if (!$urlRedirectStorageTransfer) {
            return null;
        }

        return $this->getUrlStorageTransfer($urlRedirectStorageTransfer->getToUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer|null
     */
    protected function provideRestUrlResolverAttributesTransferByUrlStorageTransfer(UrlStorageTransfer $urlStorageTransfer): ?RestUrlResolverAttributesTransfer
    {
        foreach ($this->restUrlResolverAttributesTransferProviderPlugins as $restUrlResolverAttributesTransferProviderPlugin) {
            if (!$restUrlResolverAttributesTransferProviderPlugin->isApplicable($urlStorageTransfer)) {
                continue;
            }

            return $restUrlResolverAttributesTransferProviderPlugin
                ->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);
        }

        return null;
    }
}
