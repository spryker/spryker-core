<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\AbstractAlternativeProduct;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class AbstractAlternativeProductReader implements AbstractAlternativeProductReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface
     */
    protected $productStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface
     */
    protected $alternativeProductRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface $productStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface $alternativeProductRestResponseBuilder
     */
    public function __construct(
        ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        ProductAlternativesRestApiToProductStorageClientInterface $productStorage,
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
    public function getAbstractAlternativeProductCollection(RestRequestInterface $restRequest): RestResponseInterface
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
        $abstractProductIds = $productAlternativeStorageTransfer ? $productAlternativeStorageTransfer->getProductAbstractIds() : [];

        return $this->alternativeProductRestResponseBuilder
            ->buildAbstractAlternativeProductCollectionResponse($abstractProductIds, $restRequest);
    }
}
