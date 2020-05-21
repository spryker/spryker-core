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
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface
     */
    protected $productOfferRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct(
        ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder,
        MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
    ) {
        $this->productOfferRestResponseBuilder = $productOfferRestResponseBuilder;
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
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
            return $this->productOfferRestResponseBuilder->createProductOfferIdNotSpecifierErrorResponse();
        }

        $productOfferStorageTransfer = $this->merchantProductOfferStorageClient->findProductOfferStorageByReference($merchantProductOfferReference);

        if (!$productOfferStorageTransfer) {
            return $this->productOfferRestResponseBuilder->createProductOfferNotFoundErrorResponse();
        }

        $defaultMerchantProductOfferReference = $this->getDefaultProductOfferReference($productOfferStorageTransfer);

        return $this->productOfferRestResponseBuilder->createProductOfferRestResponse(
            $productOfferStorageTransfer,
            $defaultMerchantProductOfferReference
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return string|null
     */
    protected function getDefaultProductOfferReference(ProductOfferStorageTransfer $productOfferStorageTransfer): ?string
    {
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->setSku($productOfferStorageTransfer->getProductConcreteSku());

        return $this->merchantProductOfferStorageClient->findProductConcreteDefaultProductOffer($productOfferStorageCriteriaTransfer);
    }
}
