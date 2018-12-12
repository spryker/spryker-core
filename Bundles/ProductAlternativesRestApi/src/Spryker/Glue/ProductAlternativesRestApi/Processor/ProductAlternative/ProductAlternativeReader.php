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
use Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapperInterface;
use Spryker\Glue\ProductAlternativesRestApi\ProductAlternativesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAlternativeReader implements ProductAlternativeReaderInterface
{
    protected const SELF_LINK_PATTERN = '%s/%s/%s';

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface
     */
    protected $productStorage;

    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapperInterface
     */
    protected $productAlternativeMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface $productStorage
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapperInterface $productAlternativeMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        ProductAlternativesRestApiToProductAlternativeStorageClientInterface $productAlternativeStorage,
        ProductAlternativesRestApiToProductStorageClientInterface $productStorage,
        ProductAlternativeMapperInterface $productAlternativeMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->productAlternativeStorage = $productAlternativeStorage;
        $this->productStorage = $productStorage;
        $this->productAlternativeMapper = $productAlternativeMapper;
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
            $restErrorTransfer = $this->createConcreteProductSkuIsNotSpecifiedError();

            return $restResponse->addError($restErrorTransfer);
        }

        $concreteProductSku = $concreteProductResource->getId();

        $restResource = $this->findConcreteProductAlternativeBySku($concreteProductSku, $restRequest);
        if (!$restResource) {
            $restResource = $this->buildProductAlternativeResource($concreteProductSku, new ProductAlternativeStorageTransfer(), $restRequest);
        }

        return $restResponse->addResource($restResource);
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
     * @param int $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array|null
     */
    protected function findAbstractProductById(int $abstractProductId, RestRequestInterface $restRequest): ?array
    {
        return $this->productStorage->findProductAbstractStorageData($abstractProductId, $restRequest->getMetadata()->getLocale());
    }

    /**
     * @param int $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array|null
     */
    protected function findConcreteProductById(int $concreteProductId, RestRequestInterface $restRequest): ?array
    {
        return $this->productStorage->findProductConcreteStorageData($concreteProductId, $restRequest->getMetadata()->getLocale());
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

        foreach ($productAlternativeStorageTransfer->getProductAbstractIds() as $productAbstractId) {
            $abstractProductStorageData = $this->findAbstractProductById($productAbstractId, $restRequest);
            if ($abstractProductStorageData) {
                $restProductAlternativeAttributesTransfer = $this->productAlternativeMapper
                    ->mapProductAbstractStorageDataToRestAlternativeProductsAttributesTransfer($abstractProductStorageData, $restProductAlternativeAttributesTransfer);
            }
        }
        foreach ($productAlternativeStorageTransfer->getProductConcreteIds() as $productConcreteId) {
            $concreteProductStorageData = $this->findConcreteProductById($productConcreteId, $restRequest);
            if ($concreteProductStorageData) {
                $restProductAlternativeAttributesTransfer = $this->productAlternativeMapper
                    ->mapProductConcreteStorageDataToRestAlternativeProductsAttributesTransfer($concreteProductStorageData, $restProductAlternativeAttributesTransfer);
            }
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductAlternativesRestApiConfig::RESOURCE_PRODUCT_ALTERNATIVES,
            $sku,
            $restProductAlternativeAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_PATTERN,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sku,
            ProductAlternativesRestApiConfig::RESOURCE_PRODUCT_ALTERNATIVES
        );
        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createConcreteProductSkuIsNotSpecifiedError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }
}
