<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductOfferStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Builder\PriceProductFilterTransferBuilderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiConfig;

class ProductOfferPriceReader implements ProductOfferPriceReaderInterface
{
    /**
     * @var string
     */
    protected const MAPPING_TYPE_SKU = 'sku';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

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
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Processor\Builder\PriceProductFilterTransferBuilderInterface
     */
    protected PriceProductFilterTransferBuilderInterface $priceProductFilterTransferBuilder;

    /**
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductOfferStorageClientInterface $productOfferStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client\ProductOfferPricesRestApiToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Processor\RestResponseBuilder\ProductOfferPriceRestResponseBuilderInterface $productOfferPriceRestResponseBuilder
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Processor\Builder\PriceProductFilterTransferBuilderInterface $priceProductFilterTransferBuilder
     */
    public function __construct(
        ProductOfferPricesRestApiToProductOfferStorageClientInterface $productOfferStorageClient,
        ProductOfferPricesRestApiToProductStorageClientInterface $productStorageClient,
        ProductOfferPricesRestApiToPriceProductStorageClientInterface $priceProductStorageClient,
        ProductOfferPricesRestApiToPriceProductClientInterface $priceProductClient,
        ProductOfferPriceRestResponseBuilderInterface $productOfferPriceRestResponseBuilder,
        PriceProductFilterTransferBuilderInterface $priceProductFilterTransferBuilder
    ) {
        $this->productOfferStorageClient = $productOfferStorageClient;
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->productOfferPriceRestResponseBuilder = $productOfferPriceRestResponseBuilder;
        $this->priceProductFilterTransferBuilder = $priceProductFilterTransferBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOfferPrices(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productOfferRestResource = $restRequest->findParentResourceByType(ProductOfferPricesRestApiConfig::RESOURCE_PRODUCT_OFFERS);

        if (!$productOfferRestResource || $productOfferRestResource->getId() === null) {
            return $this->productOfferPriceRestResponseBuilder->createProductOfferIdNotSpecifierErrorResponse();
        }

        $productOfferStorageTransfers = $this->productOfferStorageClient->getProductOfferStoragesByReferences([$productOfferRestResource->getId()]);

        if (!$productOfferStorageTransfers) {
            return $this->productOfferPriceRestResponseBuilder->createProductOfferNotFoundErrorResponse();
        }

        $productOfferPriceRestResources = $this->getProductOfferPriceRestResources(
            [$productOfferRestResource->getId()],
            $restRequest,
            reset($productOfferStorageTransfers),
        );

        $productOfferPriceRestResource = $productOfferPriceRestResources[$productOfferRestResource->getId()] ?? null;
        if ($productOfferPriceRestResource === null) {
            return $this->productOfferPriceRestResponseBuilder->createProductOfferAvailabilityEmptyRestResponse();
        }

        return $this->productOfferPriceRestResponseBuilder->createProductOfferAvailabilityRestResponse($productOfferPriceRestResource);
    }

    /**
     * @param array<string> $productOfferReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer|null $productOfferStorageTransfer
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getProductOfferPriceRestResources(
        array $productOfferReferences,
        RestRequestInterface $restRequest,
        ?ProductOfferStorageTransfer $productOfferStorageTransfer = null
    ): array {
        $productOfferStorageTransfers = $productOfferStorageTransfer ?
            [$productOfferStorageTransfer] :
            $this->productOfferStorageClient->getProductOfferStoragesByReferences($productOfferReferences);

        $productConcreteSkus = $this->getProductConcreteSkus($productOfferStorageTransfers);

        $productConcreteData = $this->productStorageClient
            ->getBulkProductConcreteStorageDataByMapping(static::MAPPING_TYPE_SKU, $productConcreteSkus, $restRequest->getMetadata()->getLocale());

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

                $priceProductFilterTransfer = $this->priceProductFilterTransferBuilder->build($restRequest);
                $priceProductFilterTransfer->setProductOfferReference($productOfferReference);

                $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
                    $priceProductTransfers,
                    $priceProductFilterTransfer,
                );

                $productOfferPriceRestResources[$productOfferReference] = $this->productOfferPriceRestResponseBuilder
                    ->createProductOfferPriceRestResource($currentProductPriceTransfer, $productOfferReference);
            }
        }

        return $productOfferPriceRestResources;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<string, string>
     */
    protected function getProductConcreteSkus(array $productOfferStorageTransfers): array
    {
        $productConcreteSkus = [];
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productConcreteSkus[$productOfferStorageTransfer->getProductOfferReferenceOrFail()] = $productOfferStorageTransfer->getProductConcreteSkuOrFail();
        }

        return $productConcreteSkus;
    }
}
