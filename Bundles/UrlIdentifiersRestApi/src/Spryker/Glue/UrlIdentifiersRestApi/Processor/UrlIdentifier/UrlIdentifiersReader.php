<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlIdentifiersRestApi\UrlIdentifiersRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class UrlIdentifiersReader implements UrlIdentifiersReaderInterface
{
    protected const URL_REQUEST_PARAMETER = 'url';

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected $resourceIdentifierProviderPlugins;

    /**
     * @param \Spryker\Glue\UrlIdentifiersRestApi\Dependency\Client\UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[] $resourceIdentifierProviderPlugins
     */
    public function __construct(
        UrlIdentifiersRestApiToUrlStorageClientInterface $urlStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        array $resourceIdentifierProviderPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
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
            return $this->createUrlRequestParamMissingErrorResponse();
        }

        $urlStorageTransfer = $this->getUrlStorageTransfer($urlRequestParameter);

        if (!$urlStorageTransfer) {
            return $this->createUrlNotFoundErrorResponse();
        }

        $resourceIdentifierTransfer = $this->provideResourceIdentifierByUrlStorageTransfer($urlStorageTransfer);
        if (!$resourceIdentifierTransfer) {
            return $this->createUrlNotFoundErrorResponse();
        }

        $restUrlIdentifierAttributesTransfer = (new RestUrlIdentifierAttributesTransfer())->fromArray($resourceIdentifierTransfer->toArray(), true);

        return $this->createUrlIdentifiersResourceResponse((string)$urlStorageTransfer->getIdUrl(), $restUrlIdentifierAttributesTransfer);
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
            if ($resourceIdentifierProviderPlugin->isApplicable($urlStorageTransfer)) {
                return $resourceIdentifierProviderPlugin->provideResourceIdentifierByUrlStorageTransfer($urlStorageTransfer);
            }
        }

        return null;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createUrlRequestParamMissingErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(UrlIdentifiersRestApiConfig::RESPONSE_CODE_URL_REQUEST_PARAMETER_MISSING)
                    ->setDetail(UrlIdentifiersRestApiConfig::RESPONSE_DETAIL_URL_REQUEST_PARAMETER_MISSING)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createUrlNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(UrlIdentifiersRestApiConfig::RESPONSE_CODE_URL_NOT_FOUND)
                    ->setDetail(UrlIdentifiersRestApiConfig::RESPONSE_DETAIL_URL_NOT_FOUND)
            );
    }

    /**
     * @param string $urlIdentifierId
     * @param \Generated\Shared\Transfer\RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createUrlIdentifiersResourceResponse(
        string $urlIdentifierId,
        RestUrlIdentifierAttributesTransfer $restUrlIdentifierAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()->addResource(
            $this->restResourceBuilder->createRestResource(
                UrlIdentifiersRestApiConfig::RESOURCE_URL_IDENTIFIERS,
                $urlIdentifierId,
                $restUrlIdentifierAttributesTransfer
            )
        );
    }
}
