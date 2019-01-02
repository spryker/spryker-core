<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\ProductAlternativesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AlternativeProductReader implements AlternativeProductReaderInterface
{
    protected const SELF_LINK_PATTERN = '%s/%s/%s';
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface
     */
    protected $productStorage;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface $productStorage
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        ProductAlternativesRestApiToProductStorageClientInterface $productStorage,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->productAlternativeStorage = $productAlternativeStorage;
        $this->productStorage = $productStorage;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getConcreteProductAlternative(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $concreteProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$concreteProductResource) {
            return $restResponse->addError($this->createConcreteProductSkuMissingError());
        }

        $concreteProductSku = $concreteProductResource->getId();

        $restResource = $this->findConcreteProductAlternativeBySku($concreteProductSku, $restRequest);
        if ($restResource) {
            return $restResponse->addResource($restResource);
        }

        return $restResponse->addError($this->createAlternativeProductsNotFoundError());
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductAlternativeBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $productAlternativeStorageTransfer = $this->productAlternativeStorage->findProductAlternativeStorage($sku);
        if (!$productAlternativeStorageTransfer) {
            return null;
        }

        return $this->buildProductAlternativeResource($sku, $productAlternativeStorageTransfer, $restRequest);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductAlternativeStorageTransfer $productAlternativeStorageTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildProductAlternativeResource(
        string $sku,
        ProductAlternativeStorageTransfer $productAlternativeStorageTransfer,
        RestRequestInterface $restRequest
    ): RestResourceInterface {
        $restProductAlternativeAttributesTransfer = new RestAlternativeProductsAttributesTransfer();
        $locale = $restRequest->getMetadata()->getLocale();

        foreach ($productAlternativeStorageTransfer->getProductAbstractIds() as $idProductAbstract) {
            $abstractProductStorageData = $this->productStorage->findProductAbstractStorageData($idProductAbstract, $locale);
            if ($abstractProductStorageData) {
                $restProductAlternativeAttributesTransfer->addAbstractProductId($abstractProductStorageData[static::KEY_SKU]);
            }
        }
        foreach ($productAlternativeStorageTransfer->getProductConcreteIds() as $idProductConcrete) {
            $concreteProductStorageData = $this->productStorage->findProductConcreteStorageData($idProductConcrete, $locale);
            if ($concreteProductStorageData) {
                $restProductAlternativeAttributesTransfer->addConcreteProductId($concreteProductStorageData[static::KEY_SKU]);
            }
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductAlternativesRestApiConfig::RESOURCE_ALTERNATIVE_PRODUCTS,
            $sku,
            $restProductAlternativeAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_PATTERN,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sku,
            ProductAlternativesRestApiConfig::RESOURCE_ALTERNATIVE_PRODUCTS
        );
        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createConcreteProductSkuMissingError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAlternativeProductsNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductAlternativesRestApiConfig::RESPONSE_CODE_ALTERNATIVE_PRODUCTS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductAlternativesRestApiConfig::RESPONSE_DETAIL_ALTERNATIVE_PRODUCTS_NOT_FOUND);

        return $restErrorTransfer;
    }
}
