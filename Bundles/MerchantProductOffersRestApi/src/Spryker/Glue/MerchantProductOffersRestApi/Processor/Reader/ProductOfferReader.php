<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface;
use Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface
     */
    protected $productOfferRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(
        ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder,
        MerchantProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient
    ) {
        $this->productOfferRestResponseBuilder = $productOfferRestResponseBuilder;
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOffer(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantProductOfferReference = $restRequest->getResource()->getId() ?? null;

        if (!$merchantProductOfferReference) {
            return $this->productOfferRestResponseBuilder->createProductOfferIdNotSpecifiedErrorResponse();
        }

        $productOfferStorageTransfer = $this->productOfferStorageClient
            ->findProductOfferStorageByReference($merchantProductOfferReference);

        if (!$productOfferStorageTransfer) {
            return $this->productOfferRestResponseBuilder->createProductOfferNotFoundErrorResponse();
        }

        $defaultMerchantProductOfferReference = $this->getDefaultProductOfferReference($productOfferStorageTransfer);
        if (!$defaultMerchantProductOfferReference) {
            return $this->productOfferRestResponseBuilder->createProductOfferNotFoundErrorResponse();
        }

        return $this->productOfferRestResponseBuilder->createProductOfferRestResponse(
            $productOfferStorageTransfer,
            $defaultMerchantProductOfferReference,
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOffers(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productConcreteResource = $restRequest->findParentResourceByType(MerchantProductOffersRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        $productConcreteSku = $productConcreteResource ? $productConcreteResource->getId() : null;

        if (!$productConcreteSku) {
            return $this->productOfferRestResponseBuilder->createProductConcreteSkuNotSpecifiedErrorResponse();
        }

        $productConcreteProductOfferResources = $this->getProductOfferResourcesByProductConcreteSkus([$productConcreteSku]);

        if (!isset($productConcreteProductOfferResources[$productConcreteSku])) {
            return $this->productOfferRestResponseBuilder->createProductOfferEmptyRestResponse();
        }

        return $this->productOfferRestResponseBuilder->createProductOfferCollectionRestResponse($productConcreteProductOfferResources[$productConcreteSku]);
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function getProductOfferResourcesByProductConcreteSkus(array $productConcreteSkus): array
    {
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->setProductConcreteSkus($productConcreteSkus);

        $productOfferStorageCollectionTransfer = $this->productOfferStorageClient
            ->getProductOfferStoragesBySkus($productOfferStorageCriteriaTransfer);

        return $this->productOfferRestResponseBuilder->createProductOfferRestResources($productOfferStorageCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return string|null
     */
    protected function getDefaultProductOfferReference(ProductOfferStorageTransfer $productOfferStorageTransfer): ?string
    {
        /** @var string $sku */
        $sku = $productOfferStorageTransfer->getProductConcreteSku();
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->addProductConcreteSku($sku);

        return $this->productOfferStorageClient->findProductConcreteDefaultProductOffer($productOfferStorageCriteriaTransfer);
    }
}
