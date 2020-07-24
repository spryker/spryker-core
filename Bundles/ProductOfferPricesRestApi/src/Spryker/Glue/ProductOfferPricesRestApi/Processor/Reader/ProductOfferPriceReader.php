<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiConfig;

class ProductOfferPriceReader implements ProductOfferPriceReaderInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const PRODUCT_CONCRETE_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const PRODUCT_CONCRETE_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const PRODUCT_CONCRETE_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface
     */
    protected $productOfferPriceRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface $productOfferPriceRestResponseBuilder
     */
    public function __construct(
        ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        ProductOfferPricesRestApiToProductStorageClientInterface $productStorageClient,
        ProductOfferPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient,
        ProductOfferPricesRestApiToPriceProductClientInterface $priceProductClient,
        ProductOfferPriceRestResponseBuilderInterface $productOfferPriceRestResponseBuilder
    ) {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->productOfferPriceRestResponseBuilder = $productOfferPriceRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOfferPrices(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productOfferRestResource = $restRequest->findParentResourceByType(ProductOfferPricesRestApiConfig::RESOURCE_PRODUCT_OFFERS);

        if (!$productOfferRestResource || !$productOfferRestResource->getId()) {
            return $this->productOfferPriceRestResponseBuilder->createProductOfferIdNotSpecifierErrorResponse();
        }

        $productOfferPriceRestResources = $this->getProductOfferPriceRestResources([$productOfferRestResource->getId()], $restRequest->getMetadata()->getLocale());

        $productOfferPriceRestResource = $productOfferPriceRestResources[$productOfferRestResource->getId()] ?? null;
        if (!isset($productOfferPriceRestResource)) {
            return $this->productOfferPriceRestResponseBuilder->createProductOfferAvailabilityEmptyRestResponse();
        }

        return $this->productOfferPriceRestResponseBuilder->createProductOfferAvailabilityRestResponse($productOfferPriceRestResource);
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductOfferPriceRestResources(array $productOfferReferences, string $localeName): array
    {
        $productOfferStorageTransfers = $this->merchantProductOfferStorageClient->getProductOfferStorageByReferences($productOfferReferences);

        $productConcreteSkus = $this->getProductConcreteSkus($productOfferStorageTransfers);

        $productConcreteData = $this->productStorageClient
            ->getBulkProductConcreteStorageDataByMapping(static::MAPPING_TYPE_SKU, $productConcreteSkus, $localeName);

        $productOfferPriceRestResources = [];
        foreach ($productConcreteData as $productConcreteDataItem) {
            $idProductConcrete = $productConcreteDataItem[static::PRODUCT_CONCRETE_ID_PRODUCT_CONCRETE] ?? null;
            $idProductAbstract = $productConcreteDataItem[static::PRODUCT_CONCRETE_ID_PRODUCT_ABSTRACT] ?? null;

            if (!$idProductConcrete || !$idProductAbstract) {
                continue;
            }

            $priceProductTransfers = $this->priceProductStorageClient
                ->getResolvedPriceProductConcreteTransfers($idProductConcrete, $idProductAbstract);

            foreach ($productConcreteSkus as $productOfferReference => $productConcreteSku) {
                if ($productConcreteSku !== $productConcreteDataItem[static::PRODUCT_CONCRETE_SKU]) {
                    continue;
                }

                $priceProductFilterTransfer = (new PriceProductFilterTransfer())
                    ->setProductOfferReference($productOfferReference);

                $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
                    $priceProductTransfers,
                    $priceProductFilterTransfer
                );

                $productOfferPriceRestResources[$productOfferReference] = $this->productOfferPriceRestResponseBuilder
                    ->createProductOfferPriceRestResource($currentProductPriceTransfer, $productOfferReference);
            }
        }

        return $productOfferPriceRestResources;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(array $productOfferStorageTransfers): array
    {
        $productConcreteSkus = [];
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productConcreteSkus[$productOfferStorageTransfer->getProductOfferReference()] = $productOfferStorageTransfer->getProductConcreteSku();
        }

        return $productConcreteSkus;
    }
}
