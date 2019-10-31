<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductReviewsRestApi\Processor\Reader\ProductReviewReaderInterface;
use Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig;

abstract class AbstractProductReviewResourceRelationshipExpander
{
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
     * (
     *
     * @return array
     */
    protected function createRequestParams(): array
    {
        $requestParams = [];
        $requestParams['offset'] = 0;
        $requestParams['limit'] = $this->productReviewsRestApiConfig->getMaximumNumberOfResults();

        return $requestParams;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productReviewsRestResources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addResourceRelationship(array $productReviewsRestResources, RestResourceInterface $resource): void
    {
        foreach ($productReviewsRestResources as $productReviewsRestResource) {
            $resource->addRelationship($productReviewsRestResource);
        }
    }
}
