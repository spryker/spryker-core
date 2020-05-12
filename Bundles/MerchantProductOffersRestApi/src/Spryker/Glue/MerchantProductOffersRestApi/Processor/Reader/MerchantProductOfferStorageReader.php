<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface;

class MerchantProductOfferStorageReader implements MerchantProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct(MerchantProductOffersRestApiToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient)
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
