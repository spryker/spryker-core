<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher\Dependency\Client;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

class MerchantSwitcherToMerchantProductOfferClientBridge implements MerchantSwitcherToMerchantProductOfferClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOffer\MerchantProductOfferClientInterface
     */
    protected $merchantProductOfferClient;

    /**
     * @param \Spryker\Client\MerchantProductOffer\MerchantProductOfferClientInterface $merchantProductOfferClient
     */
    public function __construct($merchantProductOfferClient)
    {
        $this->merchantProductOfferClient = $merchantProductOfferClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer
    {
        return $this->merchantProductOfferClient->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);
    }
}
