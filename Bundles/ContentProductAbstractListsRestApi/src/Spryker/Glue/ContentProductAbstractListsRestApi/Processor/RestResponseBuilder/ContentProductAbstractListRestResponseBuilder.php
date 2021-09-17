<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentProductAbstractListRestResponseBuilder implements ContentProductAbstractListRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentItemIdNotSpecifiedErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_KEY_IS_MISSING)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentItemtNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentTypeInvalidErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID)
            );
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $abstractProductResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentProductAbstractListProductsRestResponse(array $abstractProductResources): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($abstractProductResources as $abstractProductResource) {
            $restResponse->addResource($abstractProductResource);
        }

        return $restResponse;
    }

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer> $contentProductAbstractListTypeTransfers
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param array<\Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer> $contentProductAbstractListTypeTransfers
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function createContentProductAbstractListsRestResources(array $contentProductAbstractListTypeTransfers): array
    {
        $contentProductAbstractListsRestResources = [];
        foreach ($contentProductAbstractListTypeTransfers as $contentProductAbstractListKey => $contentProductAbstractListTypeTransfer) {
            $contentProductAbstractListsRestResource = $this->restResourceBuilder->createRestResource(
                ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS,
                $contentProductAbstractListKey
            );

            $contentProductAbstractListsRestResources[$contentProductAbstractListKey] = $contentProductAbstractListsRestResource;
        }

        return $contentProductAbstractListsRestResources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $contentProductAbstractListRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentProductAbstractListRestResponse(RestResourceInterface $contentProductAbstractListRestResource): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addResource($contentProductAbstractListRestResource);

        return $restResponse;
    }
}
