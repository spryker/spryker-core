<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;

class ProductReviewResourceRelationshipExpander implements ProductReviewResourceRelationshipExpanderInterface
{
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const PRODUCT_MAPPING_TYPE = 'sku';

    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface
     */
    protected $productReviewReader;

    /**
     * @var \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface $productReviewReader
     * @param \Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductReviewReaderInterface $productReviewReader,
        ProductReviewsRestApiToProductStorageClientInterface $productStorageClient
    ) {
        $this->productReviewReader = $productReviewReader;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByAbstractSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
                static::PRODUCT_MAPPING_TYPE,
                $resource->getId(),
                $restRequest->getMetadata()->getLocale()
            );

            $this->addRelationship($abstractProductData, $restRequest, $resource);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByConcreteSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $concreteProductData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
                static::PRODUCT_MAPPING_TYPE,
                $resource->getId(),
                $restRequest->getMetadata()->getLocale()
            );

            $this->addRelationship($concreteProductData, $restRequest, $resource);
        }

        return $resources;
    }

    /**
     * @param array $productData
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addRelationship(array $productData, RestRequestInterface $restRequest, RestResourceInterface $resource): void
    {
        if (!$productData) {
            return;
        }

        $requestParams = [static::PARAMETER_NAME_ITEMS_PER_PAGE => 0];

        $productReviews = $this->productReviewReader
            ->findProductReviewsByIdProductAbstract(
                $restRequest,
                $productData[static::KEY_ID_PRODUCT_ABSTRACT],
                $requestParams
            );

        foreach ($productReviews as $productReview) {
            $resource->addRelationship($productReview);
        }
    }
}
