<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\AbstractAlternativeProduct;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductsRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class AbstractAlternativeProductReader implements AbstractAlternativeProductReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductsRestResponseBuilderInterface
     */
    protected $alternativeProductsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource\ProductAlternativesRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder\AlternativeProductsRestResponseBuilderInterface $alternativeProductsRestResponseBuilder
     */
    public function __construct(
        ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        ProductAlternativesRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        AlternativeProductsRestResponseBuilderInterface $alternativeProductsRestResponseBuilder
    ) {
        $this->productAlternativeStorage = $productAlternativeStorage;
        $this->productsRestApiResource = $productsRestApiResource;
        $this->alternativeProductsRestResponseBuilder = $alternativeProductsRestResponseBuilder;
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
            return $this->alternativeProductsRestResponseBuilder->createConcreteProductSkuMissingError();
        }

        $concreteProductSku = $concreteProductResource->getId();
        $concreteProductResource = $this->productsRestApiResource->findProductConcreteBySku($concreteProductSku, $restRequest);
        if (!$concreteProductResource) {
            return $this->alternativeProductsRestResponseBuilder->createAlternativeProductsNotFoundError();
        }

        $restResponse = $this->alternativeProductsRestResponseBuilder->createRestResponse();
        $productAlternativeStorageTransfer = $this->productAlternativeStorage->findProductAlternativeStorage($concreteProductSku);
        if (!$productAlternativeStorageTransfer) {
            return $restResponse;
        }

        $this->addAbstractProductResources($restResponse, $productAlternativeStorageTransfer, $restRequest);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer $productAlternativeStorageTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAbstractProductResources(
        RestResponseInterface $restResponse,
        ProductAlternativeStorageTransfer $productAlternativeStorageTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        foreach ($productAlternativeStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $abstractProductResource = $this->productsRestApiResource->findProductAbstractById($idProductAbstract, $restRequest);
            if ($abstractProductResource) {
                $restResponse->addResource($abstractProductResource);
            }
        }

        return $restResponse;
    }
}
