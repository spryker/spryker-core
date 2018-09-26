<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ProductPricesMapperInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConcreteProductPricesReader implements ConcreteProductPricesReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const SELF_LINK_TEMPLATE = '%s/%s/%s';

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ProductPricesMapperInterface
     */
    protected $productPricesMapper;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ProductPricesMapperInterface $productPricesMapper
     */
    public function __construct(
        ProductPricesRestApiToProductStorageClientInterface $productStorageClient,
        ProductPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient,
        ProductPricesRestApiToPriceProductClientInterface $priceProductClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductPricesMapperInterface $productPricesMapper
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productPricesMapper = $productPricesMapper;
        $this->productStorageClient = $productStorageClient;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findConcreteProductPrices(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $concreteProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$concreteProductResource) {
            return $restResponse->addError($this->createConcreteProductSkuIsNotSpecifiedError());
        }

        $productConcreteSku = $concreteProductResource->getId();
        $restResource = $this->findConcreteProductPricesBySku($productConcreteSku, $restRequest);

        if (!$restResource) {
            return $restResponse->addError($this->createConcreteProductPricesNotFoundError());
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductPricesBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $concreteProductData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $sku,
                $restRequest->getMetadata()->getLocale()
            );
        if (!$concreteProductData) {
            return null;
        }

        $priceProductTransfers = $this
            ->priceProductStorageClient
            ->resolvePriceProductConcrete(
                $concreteProductData[static::KEY_ID_PRODUCT_CONCRETE],
                $concreteProductData[static::KEY_ID_PRODUCT_ABSTRACT]
            );

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);

        $restProductPricesAttributesTransfer = $this->productPricesMapper
            ->mapCurrentProductPriceTransferToRestProductPricesAttributesTransfer($currentProductPriceTransfer);

        return $this->buildProductPricesResource($sku, $restProductPricesAttributesTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $restProductPricesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildProductPricesResource(string $sku, RestProductPricesAttributesTransfer $restProductPricesAttributesTransfer): ?RestResourceInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
            $sku,
            $restProductPricesAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_TEMPLATE,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sku,
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES
        );
        $restResource->addLink(RestResourceInterface::RESOURCE_LINKS_SELF, $restResourceSelfLink);

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

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createConcreteProductPricesNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_PRICES_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_CONCRETE_PRODUCT_PRICES_NOT_FOUND);

        return $restErrorTransfer;
    }
}
