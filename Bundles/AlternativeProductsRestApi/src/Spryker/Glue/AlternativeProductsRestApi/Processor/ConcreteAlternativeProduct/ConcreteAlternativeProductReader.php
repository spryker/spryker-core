<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi\Processor\ConcreteAlternativeProduct;

use Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductStorageClientInterface;
use Spryker\Glue\AlternativeProductsRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ConcreteAlternativeProductReader implements ConcreteAlternativeProductReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorage;

    /**
     * @var \Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductStorageClientInterface
     */
    protected $productStorage;

    /**
     * @var \Spryker\Glue\AlternativeProductsRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface
     */
    protected $alternativeProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\AlternativeProductsRestApi\Dependency\Client\AlternativeProductsRestApiToProductStorageClientInterface $productStorage
     * @param \Spryker\Glue\AlternativeProductsRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface $alternativeProductRestResponseBuilder
     */
    public function __construct(
        AlternativeProductsRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        AlternativeProductsRestApiToProductStorageClientInterface $productStorage,
        AlternativeProductRestResponseBuilderInterface $alternativeProductRestResponseBuilder
    ) {
        $this->productAlternativeStorage = $productAlternativeStorage;
        $this->productStorage = $productStorage;
        $this->alternativeProductRestResponseBuilder = $alternativeProductRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getConcreteAlternativeProductCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $concreteProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$concreteProductResource) {
            return $this->alternativeProductRestResponseBuilder->createConcreteProductSkuMissingError();
        }

        $concreteProductSku = $concreteProductResource->getId();
        $concreteProductStorageData = $this->productStorage->findProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $concreteProductSku,
            $restRequest->getMetadata()->getLocale()
        );
        if (!$concreteProductStorageData) {
            return $this->alternativeProductRestResponseBuilder->createConcreteProductNotFoundError();
        }

        $productAlternativeStorageTransfer = $this->productAlternativeStorage->findProductAlternativeStorage($concreteProductSku);
        $concreteProductIds = $productAlternativeStorageTransfer ? $productAlternativeStorageTransfer->getProductConcreteIds() : [];

        return $this->alternativeProductRestResponseBuilder
            ->buildConcreteAlternativeProductCollectionResponse($concreteProductIds, $restRequest);
    }
}
