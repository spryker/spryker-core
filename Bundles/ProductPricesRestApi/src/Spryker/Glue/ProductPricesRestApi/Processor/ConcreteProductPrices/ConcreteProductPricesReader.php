<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\ConcreteProductPrices;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ConcreteProductPricesReader implements ConcreteProductPricesReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface
     */
    protected $concreteProductPricesResourceMapper;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\Mapper\ConcreteProductPricesResourceMapperInterface $concreteProductPricesResourceMapper
     */
    public function __construct(
        ProductPricesRestApiToPriceProductResourceAliasStorageClientInterface $priceProductStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ConcreteProductPricesResourceMapperInterface $concreteProductPricesResourceMapper
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->concreteProductPricesResourceMapper = $concreteProductPricesResourceMapper;
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
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_MISSING);

            return $restResponse->addError($restErrorTransfer);
        }

        $productConcreteSku = $concreteProductResource->getId();
        $priceProductStorageTransfer = $this->priceProductStorageClient->findPriceProductConcreteStorageTransfer($productConcreteSku);

        if ($priceProductStorageTransfer === null) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_PRICES_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_CONCRETE_PRODUCT_PRICES_NOT_FOUND);

            return $restResponse->addError($restErrorTransfer);
        }

        $restResource = $this->concreteProductPricesResourceMapper->mapConcreteProductPricesTransferToRestResource($priceProductStorageTransfer, $productConcreteSku);

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $idConcreteProduct
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductPricesByConcreteProductId(string $idConcreteProduct, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $priceProductStorageTransfer = $this->priceProductStorageClient->findPriceProductConcreteStorageTransfer($idConcreteProduct);
        if (!$priceProductStorageTransfer) {
            return null;
        }

        return $this->concreteProductPricesResourceMapper->mapConcreteProductPricesTransferToRestResource($priceProductStorageTransfer, $idConcreteProduct);
    }
}
