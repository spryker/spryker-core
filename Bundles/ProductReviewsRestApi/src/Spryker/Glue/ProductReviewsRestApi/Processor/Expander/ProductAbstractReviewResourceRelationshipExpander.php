<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

class ProductAbstractReviewResourceRelationshipExpander implements ProductAbstractReviewResourceRelationshipExpanderInterface
{
    /**
     * @uses \Spryker\Client\ProductStorage\Mapper\ProductStorageToProductConcreteTransferDataMapper::ID_PRODUCT_ABSTRACT
     */
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_SKU = 'sku';
    protected const PRODUCT_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface
     */
    protected $productReviewReader;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig
     */
    protected $productReviewsRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface $productReviewReader
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig $productReviewsRestApiConfig
     */
    public function __construct(
        ProductReviewReaderInterface $productReviewReader,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient,
        ProductReviewsRestApiConfig $productReviewsRestApiConfig
    ) {
        $this->productReviewReader = $productReviewReader;
        $this->productStorageClient = $productStorageClient;
        $this->productReviewsRestApiConfig = $productReviewsRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByAbstractSku(array $resources, RestRequestInterface $restRequest): void
    {
        $productAbstractSkus = $this->getAllSkus($resources);

        $productAbstractDataCollection = $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $productAbstractSkus,
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productAbstractDataCollection) {
            return;
        }

        $productAbstractIds = [];
        foreach ($productAbstractDataCollection as $productAbstractData) {
            $productAbstractIds[] = $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productReviewsRestResourcesCollection = $this->productReviewReader
            ->getProductReviewsResourceCollection(
                $productAbstractIds,
                $this->createFilterTransfer()
            );

        foreach ($resources as $resource) {
            $this->addProductReviewsRelationships(
                $productReviewsRestResourcesCollection,
                $productAbstractDataCollection,
                $resource
            );
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][] $productReviewsRestResourcesCollection
     * @param array $productAbstractDataCollection
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addProductReviewsRelationships(
        array $productReviewsRestResourcesCollection,
        array $productAbstractDataCollection,
        RestResourceInterface $resource
    ): void {
        foreach ($productReviewsRestResourcesCollection as $idProductAbstract => $productReviewsRestResources) {
            if (!array_key_exists($idProductAbstract, $productAbstractDataCollection)) {
                continue;
            }

            $productAbstractData = $productAbstractDataCollection[$idProductAbstract];
            if (
                $resource->getId() !== $productAbstractData[static::KEY_SKU]
                || $productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT] !== $idProductAbstract
            ) {
                continue;
            }

            $this->addResourceRelationship($resource, $productReviewsRestResources);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getAllSkus(array $resources): array
    {
        $skus = [];
        foreach ($resources as $resource) {
            $skus[] = $resource->getId();
        }

        return $skus;
    }

    /**
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset(0)
            ->setLimit($this->productReviewsRestApiConfig->getMaximumNumberOfResults());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productReviewsRestResources
     *
     * @return void
     */
    protected function addResourceRelationship(RestResourceInterface $resource, array $productReviewsRestResources): void
    {
        foreach ($productReviewsRestResources as $productReviewsRestResource) {
            $resource->addRelationship($productReviewsRestResource);
        }
    }
}
