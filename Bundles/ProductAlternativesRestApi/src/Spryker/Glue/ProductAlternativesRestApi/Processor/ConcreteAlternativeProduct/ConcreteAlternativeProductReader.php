<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\ConcreteAlternativeProduct;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ConcreteAlternativeProductReader implements ConcreteAlternativeProductReaderInterface
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
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface
     */
    protected $alternativeProductsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface $productStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductRestResponseBuilderInterface $alternativeProductsRestResponseBuilder
     */
    public function __construct(
        ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        ProductAlternativesRestApiToProductStorageClientInterface $productStorage,
        ProductAlternativesRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        AlternativeProductRestResponseBuilderInterface $alternativeProductsRestResponseBuilder
    ) {
        $this->productAlternativeStorage = $productAlternativeStorage;
        $this->productStorage = $productStorage;
        $this->productsRestApiResource = $productsRestApiResource;
        $this->alternativeProductsRestResponseBuilder = $alternativeProductsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getConcreteAlternativeProductCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->alternativeProductsRestResponseBuilder->createResourceNotFoundError();
        }

        $concreteProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$concreteProductResource) {
            return $this->alternativeProductsRestResponseBuilder->createConcreteProductSkuMissingError();
        }

        $concreteProductSku = $concreteProductResource->getId();
        $concreteProductStorageData = $this->productStorage->findProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $concreteProductSku,
            $restRequest->getMetadata()->getLocale()
        );
        if (!$concreteProductStorageData) {
            return $this->alternativeProductsRestResponseBuilder->createConcreteProductNotFoundError();
        }

        return $this->buildRestResponse(
            $this->productAlternativeStorage->findProductAlternativeStorage($concreteProductSku),
            $restRequest
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null $productAlternativeStorageTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildRestResponse(
        ?ProductAlternativeStorageTransfer $productAlternativeStorageTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->alternativeProductsRestResponseBuilder->createRestResponse();
        if (!$productAlternativeStorageTransfer) {
            return $restResponse;
        }

        foreach ($productAlternativeStorageTransfer->getProductConcreteIds() as $idProductConcrete) {
            $concreteProductResource = $this->productsRestApiResource->findProductConcreteById($idProductConcrete, $restRequest);
            if ($concreteProductResource) {
                $restResponse->addResource($concreteProductResource);
            }
        }

        return $restResponse;
    }
}
