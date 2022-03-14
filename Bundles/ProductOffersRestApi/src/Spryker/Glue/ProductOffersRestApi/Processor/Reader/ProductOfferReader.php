<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Processor\Reader;

use ArrayObject;
use Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientInterface;
use Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @var \Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface
     */
    protected $productOfferRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder\ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder
     * @param \Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(
        ProductOfferRestResponseBuilderInterface $productOfferRestResponseBuilder,
        ProductOffersRestApiToProductOfferStorageClientInterface $productOfferStorageClient
    ) {
        $this->productOfferRestResponseBuilder = $productOfferRestResponseBuilder;
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function getProductOfferResourcesByProductOfferReferences(array $productOfferReferences): array
    {
        $productOfferStorageTransfers = $this->productOfferStorageClient
            ->getProductOfferStoragesByReferences($productOfferReferences);

        return $this->productOfferRestResponseBuilder->createProductOfferRestResourcesByReference(new ArrayObject($productOfferStorageTransfers));
    }
}
