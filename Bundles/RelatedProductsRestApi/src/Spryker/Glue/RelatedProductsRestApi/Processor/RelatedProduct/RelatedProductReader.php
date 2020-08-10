<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientInterface;
use Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilderInterface;

class RelatedProductReader implements RelatedProductReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilderInterface
     */
    protected $relatedProductRestResponseBuilder;

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Processor\RestResponseBuilder\RelatedProductRestResponseBuilderInterface $relatedProductRestResponseBuilder
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        RelatedProductsRestApiToProductStorageClientInterface $productStorageClient,
        RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        RelatedProductRestResponseBuilderInterface $relatedProductRestResponseBuilder,
        RelatedProductsRestApiToStoreClientInterface $storeClient
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->relatedProductRestResponseBuilder = $relatedProductRestResponseBuilder;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readRelatedProducts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource || !$parentResource->getId()) {
            return $this->relatedProductRestResponseBuilder->createProductAbstractSkuMissingError();
        }

        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $parentResource->getId(),
                $restRequest->getMetadata()->getLocale()
            );

        if (!$abstractProductData) {
            return $this->relatedProductRestResponseBuilder->createProductAbstractNotFoundError();
        }

        $storeName = $this->storeClient->getCurrentStore()->getName();
        $relatedProductAbstractIds = $this->productRelationStorageClient
            ->findRelatedAbstractProductIds($abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT], $storeName);

        return $this->relatedProductRestResponseBuilder
            ->buildRelatedProductCollectionRestResponse($restRequest, $relatedProductAbstractIds);
    }
}
