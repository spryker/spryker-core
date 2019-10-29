<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Page;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

class ProductReviewResourceRelationshipExpander implements ProductReviewResourceRelationshipExpanderInterface
{
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

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
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig
     */
    protected $productReviewsRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface $productReviewReader
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface $productReviewClient
     * @param \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig $productReviewsRestApiConfig
     */
    public function __construct(
        ProductReviewReaderInterface $productReviewReader,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient,
        ProductReviewsRestApiToProductReviewClientInterface $productReviewClient,
        ProductReviewsRestApiConfig $productReviewsRestApiConfig
    ) {
        $this->productReviewReader = $productReviewReader;
        $this->productStorageClient = $productStorageClient;
        $this->productReviewClient = $productReviewClient;
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
        $productAbstractIds = [];
        foreach ($resources as $resource) {
            $productAbstractIds[] = $resource->getId();
        }

        $productAbstractResources = $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $productAbstractIds,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($resources as $resource) {
            $this->addRelationship($productAbstractResources, $restRequest, $resource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addRelationshipsByConcreteSku(array $resources, RestRequestInterface $restRequest): void
    {
        $productConcreteIds = [];
        foreach ($resources as $resource) {
            $productConcreteIds[] = $resource->getId();
        }

        $productConcreteResources = $this->productStorageClient->getProductConcreteStorageDataByMappingAndIdentifiers(
            static::PRODUCT_MAPPING_TYPE,
            $productConcreteIds,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($resources as $resource) {
            $this->addRelationship($productConcreteResources, $restRequest, $resource);
        }
    }

    /**
     * @param array $productsData
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addRelationship(
        array $productsData,
        RestRequestInterface $restRequest,
        RestResourceInterface $resource
    ): void {
        if (!$productsData) {
            return;
        }

        $productAbstractIds = [];
        foreach ($productsData as $productData) {
            $productAbstractIds[] = $productData[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $restRequest->setPage(new Page(0, $this->productReviewsRestApiConfig->getMaximumNumberOfResults()));

        $productReviewsRestResources = $this->productReviewReader
            ->getProductReviewsByProductAbstractIds(
                $restRequest,
                $productAbstractIds,
                []
            );

        foreach ($productReviewsRestResources as $productReviewsRestResource) {
            $resource->addRelationship($productReviewsRestResource);
        }
    }
}
