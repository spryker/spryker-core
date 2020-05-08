<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class MerchantProductOffersRestApiToMerchantProductOfferStorageClientBridge implements MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
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
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        return $this->merchantProductOfferStorageClient->findProductOfferStorageByReference($productOfferReference);
    }
}
