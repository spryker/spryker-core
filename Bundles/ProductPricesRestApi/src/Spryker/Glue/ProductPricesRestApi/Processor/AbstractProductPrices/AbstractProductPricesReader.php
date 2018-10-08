<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices;

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

class AbstractProductPricesReader implements AbstractProductPricesReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
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
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ProductPricesMapperInterface
     */
    protected $productPricesMapper;

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
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productPricesMapper = $productPricesMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findAbstractProductPrices(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $abstractProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$abstractProductResource) {
            return $restResponse->addError($this->createAbstractProductSkuIsNotSpecifiedError());
        }
        $abstractProductSku = $abstractProductResource->getId();
        $restResource = $this->findAbstractProductPricesBySku($abstractProductSku, $restRequest);

        if (!$restResource) {
            return $restResponse->addError($this->createAbstractProductPricesNotFoundError());
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductPricesBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $restRequest->getMetadata()->getLocale()
            );
        if (!$abstractProductData) {
            return null;
        }

        $priceProductTransfers = $this->priceProductStorageClient
            ->getPriceProductAbstractTransfers($abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT]);

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
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            $sku,
            $restProductPricesAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            static::SELF_LINK_TEMPLATE,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $sku,
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES
        );
        $restResource->addLink(RestResourceInterface::RESOURCE_LINKS_SELF, $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductSkuIsNotSpecifiedError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductPricesNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_PRICES_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_ABSTRACT_PRODUCT_PRICES_NOT_FOUND);

        return $restErrorTransfer;
    }
}
