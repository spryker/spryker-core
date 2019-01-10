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
use Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface;

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
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct\RelatedProductRestResponseBuilderInterface
     */
    protected $relatedProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Client\RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct\RelatedProductRestResponseBuilderInterface $relatedProductRestResponseBuilder
     */
    public function __construct(
        RelatedProductsRestApiToProductStorageClientInterface $productStorageClient,
        RelatedProductsRestApiToProductRelationStorageClientInterface $productRelationStorageClient,
        RelatedProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        RelatedProductRestResponseBuilderInterface $relatedProductRestResponseBuilder
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productRelationStorageClient = $productRelationStorageClient;
        $this->productsRestApiResource = $productsRestApiResource;
        $this->relatedProductRestResponseBuilder = $relatedProductRestResponseBuilder;
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

        $sku = $parentResource->getId();
        $localeName = $restRequest->getMetadata()->getLocale();

        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $localeName
            );

        if (!$abstractProductData) {
            return $this->relatedProductRestResponseBuilder->createProductAbstractNotFoundError();
        }

        $relatedProductsEntityTransfer = $this->productRelationStorageClient
            ->findRelatedProducts($abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT], $localeName);

        return $this->relatedProductRestResponseBuilder
            ->createAbstractProductsRestResponse($restRequest, $relatedProductsEntityTransfer);
    }
}
