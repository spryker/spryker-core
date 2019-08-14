<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Reader;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapperInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface;

class UrlIdentifierReader implements UrlIdentifierReaderInterface
{
    protected const URL_REQUEST_PARAMETER = 'url';

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface
     */
    protected $urlIdentifierResponseBuilder;

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapperInterface
     */
    protected $urlIdentifierMapper;

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected $resourceIdentifierProviderPlugins;

    /**
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface $urlIdentifierResponseBuilder
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper\UrlIdentifierMapperInterface $urlIdentifierMapper
     * @param \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[] $resourceIdentifierProviderPlugins
     */
    public function __construct(
        UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient,
        UrlIdentifierResponseBuilderInterface $urlIdentifierResponseBuilder,
        UrlIdentifierMapperInterface $urlIdentifierMapper,
        array $resourceIdentifierProviderPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->urlIdentifierResponseBuilder = $urlIdentifierResponseBuilder;
        $this->urlIdentifierMapper = $urlIdentifierMapper;
        $this->resourceIdentifierProviderPlugins = $resourceIdentifierProviderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getUrlIdentifier(RestRequestInterface $restRequest): RestResponseInterface
    {
        $urlRequestParameter = urldecode($restRequest->getHttpRequest()->query->get(static::URL_REQUEST_PARAMETER));

        if (!$urlRequestParameter) {
            return $this->urlIdentifierResponseBuilder->createUrlRequestParamMissingErrorResponse();
        }

        $urlStorageTransfer = $this->getUrlStorageTransfer($urlRequestParameter);

        if (!$urlStorageTransfer) {
            return $this->urlIdentifierResponseBuilder->createUrlNotFoundErrorResponse();
        }

        $resourceIdentifierTransfer = $this->provideResourceIdentifierByUrlStorageTransfer($urlStorageTransfer);
        if (!$resourceIdentifierTransfer) {
            return $this->urlIdentifierResponseBuilder->createUrlNotFoundErrorResponse();
        }

        $restUrlIdentifiersAttributesTransfer = $this->urlIdentifierMapper->mapResourceIdentifierTransferToRestUrlIdentifiersAttributesTransfer(
            $resourceIdentifierTransfer,
            new RestUrlIdentifiersAttributesTransfer()
        );

        return $this->urlIdentifierResponseBuilder->createUrlIdentifiersResourceResponse(
            (string)$urlStorageTransfer->getIdUrl(),
            $restUrlIdentifiersAttributesTransfer
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
