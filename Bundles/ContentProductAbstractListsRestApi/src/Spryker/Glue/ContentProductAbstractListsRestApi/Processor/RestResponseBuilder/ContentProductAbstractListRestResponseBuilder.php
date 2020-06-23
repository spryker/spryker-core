<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
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
    public function addContentItemIdNotSpecifiedError(): RestResponseInterface
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
    public function addContentItemtNotFoundError(): RestResponseInterface
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
    public function addContentTypeInvalidError(): RestResponseInterface
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
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentProductAbstractListsRestResponse(
        ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer,
        string $localeName,
        string $storeName
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $productAbstractIds = $contentProductAbstractListTypeTransfer->getIdProductAbstracts();
        $abstractProductResources = $this->productsRestApiResource->getProductAbstractsByIds($productAbstractIds, $localeName, $storeName);
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
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[] $contentProductAbstractListTypeTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createContentProductAbstractListsRestResources(
        array $contentProductAbstractListTypeTransfers,
        string $localeName,
        string $storeName
    ): array {
        $abstractProductRestResources = $this->getAbstractProductRestResources($contentProductAbstractListTypeTransfers, $localeName, $storeName);

        return $this->getContentProductAbstractListsRestResources($contentProductAbstractListTypeTransfers, $abstractProductRestResources);
    }

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer> $contentProductAbstractListTypeTransfers
     *
     * @phpstan-return array<int, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[] $contentProductAbstractListTypeTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function getAbstractProductRestResources(
        array $contentProductAbstractListTypeTransfers,
        string $localeName,
        string $storeName
    ): array {
        $productAbstractIds = [];
        foreach ($contentProductAbstractListTypeTransfers as $contentProductAbstractListKey => $contentProductAbstractListTypeTransfer) {
            $productAbstractIds = array_merge($productAbstractIds, $contentProductAbstractListTypeTransfer->getIdProductAbstracts());
        }

        return $this->productsRestApiResource->getProductAbstractsByIds($productAbstractIds, $localeName, $storeName);
    }

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer> $contentProductAbstractListTypeTransfers
     * @phpstan-param array<int, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $abstractProductRestResources
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[] $contentProductAbstractListTypeTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $abstractProductRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function getContentProductAbstractListsRestResources(array $contentProductAbstractListTypeTransfers, array $abstractProductRestResources): array
    {
        $contentProductAbstractListsRestResources = [];
        foreach ($contentProductAbstractListTypeTransfers as $contentProductAbstractListKey => $contentProductAbstractListTypeTransfer) {
            $contentProductAbstractListsRestResource = $this->restResourceBuilder->createRestResource(
                ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS,
                $contentProductAbstractListKey
            );

            $contentProductAbstractListsRestResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getContentProductAbstractListsResourceSelfLink($contentProductAbstractListKey)
            );

            foreach ($contentProductAbstractListTypeTransfer->getIdProductAbstracts() as $productAbstractId) {
                $contentProductAbstractListsRestResource->addRelationship($abstractProductRestResources[$productAbstractId]);
            }

            $contentProductAbstractListsRestResources[$contentProductAbstractListKey] = $contentProductAbstractListsRestResource;
        }

        return $contentProductAbstractListsRestResources;
    }

    /**
     * @param string $contentProductAbstractListKey
     *
     * @return string
     */
    protected function getContentProductAbstractListsResourceSelfLink(string $contentProductAbstractListKey): string
    {
        return sprintf(
            '%s/%s/%s',
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS,
            $contentProductAbstractListKey,
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_PRODUCTS
        );
    }
}
