<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Url\Reader;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestUrlsAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapperInterface;
use Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface;

class UrlReader implements UrlReaderInterface
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
     * @var \Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapperInterface
     */
    protected $urlMapper;

    /**
     * @var \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected $resourceIdentifierProviderPlugins;

    /**
     * @param \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\UrlsRestApi\Processor\Url\ResponseBuilder\UrlResponseBuilderInterface $urlResponseBuilder
     * @param \Spryker\Glue\UrlsRestApi\Processor\Url\Mapper\UrlMapperInterface $urlMapper
     * @param \Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[] $resourceIdentifierProviderPlugins
     */
    public function __construct(
        UrlsRestApiToUrlStorageClientInterface $urlStorageClient,
        UrlResponseBuilderInterface $urlResponseBuilder,
        UrlMapperInterface $urlMapper,
        array $resourceIdentifierProviderPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->urlResponseBuilder = $urlResponseBuilder;
        $this->urlMapper = $urlMapper;
        $this->resourceIdentifierProviderPlugins = $resourceIdentifierProviderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getUrl(RestRequestInterface $restRequest): RestResponseInterface
    {
        $urlRequestParameter = urldecode($restRequest->getHttpRequest()->query->get(static::URL_REQUEST_PARAMETER));

        if (!$urlRequestParameter) {
            return $this->urlResponseBuilder->createUrlRequestParamMissingErrorResponse();
        }

        $urlStorageTransfer = $this->getUrlStorageTransfer($urlRequestParameter);

        if (!$urlStorageTransfer) {
            return $this->urlResponseBuilder->createUrlNotFoundErrorResponse();
        }

        $resourceIdentifierTransfer = $this->provideResourceIdentifierByUrlStorageTransfer($urlStorageTransfer);
        if (!$resourceIdentifierTransfer) {
            return $this->urlResponseBuilder->createUrlNotFoundErrorResponse();
        }

        $restUrlsAttributesTransfer = $this->urlMapper->mapResourceIdentifierTransferToRestUrlsAttributesTransfer(
            $resourceIdentifierTransfer,
            new RestUrlsAttributesTransfer()
        );

        return $this->urlResponseBuilder->createUrlsResourceResponse(
            (string)$urlStorageTransfer->getIdUrl(),
            $restUrlsAttributesTransfer
        );
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
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer|null
     */
    protected function provideResourceIdentifierByUrlStorageTransfer(UrlStorageTransfer $urlStorageTransfer): ?ResourceIdentifierTransfer
    {
        foreach ($this->resourceIdentifierProviderPlugins as $resourceIdentifierProviderPlugin) {
            if (!$resourceIdentifierProviderPlugin->isApplicable($urlStorageTransfer)) {
                continue;
            }

            return $resourceIdentifierProviderPlugin->provideResourceIdentifierByUrlStorageTransfer($urlStorageTransfer);
        }

        return null;
    }
}
