<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Reader;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface;

class UrlIdentifiersReader implements UrlIdentifiersReaderInterface
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
     * @var \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected $resourceIdentifierProviderPlugins;

    /**
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\ResponseBuilder\UrlIdentifierResponseBuilderInterface $urlIdentifierResponseBuilder
     * @param array $resourceIdentifierProviderPlugins
     */
    public function __construct(
        UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient,
        UrlIdentifierResponseBuilderInterface $urlIdentifierResponseBuilder,
        array $resourceIdentifierProviderPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->urlIdentifierResponseBuilder = $urlIdentifierResponseBuilder;
        $this->resourceIdentifierProviderPlugins = $resourceIdentifierProviderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getUrlIdentifier(RestRequestInterface $restRequest): RestResponseInterface
    {
        $urlRequestParameter = $restRequest->getHttpRequest()->get(static::URL_REQUEST_PARAMETER);

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

        $restUrlIdentifierAttributesTransfer = $this->mapResourceIdentifierTransferToRestUrlIdentifierAttributesTransfer(
            $resourceIdentifierTransfer,
            new RestUrlIdentifierAttributesTransfer()
        );

        return $this->urlIdentifierResponseBuilder->createUrlIdentifiersResourceResponse((string)$urlStorageTransfer->getIdUrl(), $restUrlIdentifierAttributesTransfer);
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

        $urlStorageTransfer = $this->urlStorageClient->findUrlRedirectStorageById(
            $urlStorageTransfer->getFkResourceRedirect()
        );

        return $this->getUrlStorageTransfer($urlStorageTransfer->getToUrl());
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

    /**
     * @param \Generated\Shared\Transfer\ResourceIdentifierTransfer $resourceIdentifierTransfer
     * @param \Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer
     */
    protected function mapResourceIdentifierTransferToRestUrlIdentifierAttributesTransfer(
        ResourceIdentifierTransfer $resourceIdentifierTransfer,
        RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer
    ): RestUrlIdentifierAttributesTransfer {
        return $restUrlIdentifierAttributesTransfer->fromArray($resourceIdentifierTransfer->toArray(), true);
    }
}
