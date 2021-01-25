<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client;

class ProductOfferPricesRestApiToMerchantProductOfferStorageClientBridge implements ProductOfferPricesRestApiToMerchantProductOfferStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct($merchantProductOfferStorageClient)
    {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferStorageByReferences(array $productOfferReferences): array
    {
        return $this->merchantProductOfferStorageClient->getProductOfferStorageByReferences($productOfferReferences);
    }
}
