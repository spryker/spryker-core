<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\Reader;

use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class ContentProductAbstractListProductReader implements ContentProductAbstractListProductReaderInterface
{
    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface
     */
    protected $contentProductAbstractListRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder\ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client\ContentProductAbstractListsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        ContentProductAbstractListsRestApiToContentProductClientInterface $contentProductClient,
        ContentProductAbstractListRestResponseBuilderInterface $contentProductAbstractListRestResponseBuilder,
        ContentProductAbstractListsRestApiToStoreClientInterface $storeClient,
        ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->contentProductClient = $contentProductClient;
        $this->contentProductAbstractListRestResponseBuilder = $contentProductAbstractListRestResponseBuilder;
        $this->storeClient = $storeClient;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductAbstractByContentProductAbstractListId(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS
        );

        if (!$parentResource || !$parentResource->getId()) {
            return $this->contentProductAbstractListRestResponseBuilder->createContentItemIdNotSpecifiedErrorResponse();
        }

        $localeName = $restRequest->getMetadata()->getLocale();
        try {
            $contentProductAbstractListTypeTransfer = $this->contentProductClient->executeProductAbstractListTypeByKey(
                $parentResource->getId(),
                $localeName
            );
        } catch (Throwable $e) {
            return $this->contentProductAbstractListRestResponseBuilder->createContentTypeInvalidErrorResponse();
        }

        if (!$contentProductAbstractListTypeTransfer) {
            return $this->contentProductAbstractListRestResponseBuilder->createContentItemtNotFoundErrorResponse();
        }

        $abstractProductResources = $this->productsRestApiResource->getProductAbstractsByIds(
            $contentProductAbstractListTypeTransfer->getIdProductAbstracts(),
            $localeName,
            $this->storeClient->getCurrentStore()->getName()
        );

        return $this->contentProductAbstractListRestResponseBuilder
            ->createContentProductAbstractListProductsRestResponse($abstractProductResources);
    }

    /**
     * @phpstan-return array<string, array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param string[] $contentProductAbstractListKeys
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductAbstractRestResources(array $contentProductAbstractListKeys, string $localeName): array
    {
        $contentProductAbstractListTypeTransfers = $this->contentProductClient->executeProductAbstractListTypeByKeys(
            $contentProductAbstractListKeys,
            $localeName
        );

        if (!$contentProductAbstractListTypeTransfers) {
            return [];
        }

        $productAbstractIds = [];
        foreach ($contentProductAbstractListTypeTransfers as $contentProductAbstractListKey => $contentProductAbstractListTypeTransfer) {
            $productAbstractIds[$contentProductAbstractListKey] = $contentProductAbstractListTypeTransfer->getIdProductAbstracts();
        }

        $productAbstractRestResources = $this->productsRestApiResource->getProductAbstractsByIds(
            array_merge(...array_values($productAbstractIds)),
            $localeName,
            $this->storeClient->getCurrentStore()->getName()
        );

        return $this->groupProductAbstractsByContentProductAbstractListKey(
            $productAbstractRestResources,
            $productAbstractIds
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productAbstractRestResources
     * @param int[][] $contentProductAbstractListIds
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    protected function groupProductAbstractsByContentProductAbstractListKey(
        array $productAbstractRestResources,
        array $contentProductAbstractListIds
    ): array {
        $groupedProductAbstractRestResources = [];
        foreach ($productAbstractRestResources as $idProductAbstract => $productAbstractRestResource) {
            foreach ($contentProductAbstractListIds as $contentProductAbstractListKey => $productAbstractIds) {
                if (!in_array($idProductAbstract, $productAbstractIds)) {
                    continue;
                }

                $groupedProductAbstractRestResources[$contentProductAbstractListKey][] = $productAbstractRestResource;
            }
        }

        return $groupedProductAbstractRestResources;
    }
}
