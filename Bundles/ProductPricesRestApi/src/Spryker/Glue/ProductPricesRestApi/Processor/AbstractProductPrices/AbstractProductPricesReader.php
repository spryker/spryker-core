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
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_MISSING);

            return $restResponse->addError($restErrorTransfer);
        }
        $abstractProductSku = $abstractProductResource->getId();

        $priceProductStorageTransfer = $this->priceProductStorageClient
            ->findPriceProductAbstractStorageTransfer($abstractProductSku);

        if ($priceProductStorageTransfer === null) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_PRICES_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_ABSTRACT_PRODUCT_PRICES_NOT_FOUND);

            return $restResponse->addError($restErrorTransfer);
        }

        $restResource = $this->abstractProductPricesResourceMapper->mapAbstractProductPricesTransferToRestResource($priceProductStorageTransfer, $abstractProductSku);

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

        return $this->abstractProductPricesResourceMapper
            ->mapAbstractProductPricesTransferToRestResource($priceProductStorageTransfer, $abstractProductSku);
    }
}
