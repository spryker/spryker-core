<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductPricesReader implements AbstractProductPricesReaderInterface
{
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
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface
     */
    protected $abstractProductPricesResourceMapper;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\AbstractProductPricesResourceMapperInterface $abstractProductPricesResourceMapper
     */
    public function __construct(
        ProductPricesRestApiToProductStorageClientInterface $productStorageClient,
        ProductPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductPricesResourceMapperInterface $abstractProductPricesResourceMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->abstractProductPricesResourceMapper = $abstractProductPricesResourceMapper;
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
        $restResource = $this->findAbstractProductPricesByAbstractProductSku($abstractProductSku, $restRequest);

        if (!$restResource) {
            return $restResponse->addError($this->createAbstractProductPricesNotFoundError());
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductPricesByAbstractProductSku(string $abstractProductSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $priceProductStorageTransfer = $this->priceProductStorageClient
            ->findPriceProductAbstractStorageTransfer($abstractProductSku);
        if (!$priceProductStorageTransfer) {
            return null;
        }

        $restProductPricesAttributesTransfer = $this->abstractProductPricesResourceMapper
            ->mapAbstractProductPricesTransferToRestProductPricesAttributesTransfer($priceProductStorageTransfer, $abstractProductSku);

        return $this->buildPorductPricesResource($abstractProductSku, $restProductPricesAttributesTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildPorductPricesResource(string $sku, RestProductPricesAttributesTransfer $restProductPricesAttributesTransfer) {
        return $this->restResourceBuilder->createRestResource(
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            $sku,
            $restProductPricesAttributesTransfer
        );
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
