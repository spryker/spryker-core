<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

class MerchantSwitcherToMerchantProductOfferFacadeBridge implements MerchantSwitcherToMerchantProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface
     */
    protected $merchantProductOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface $merchantProductOfferFacade
     */
    public function __construct($merchantProductOfferFacade)
    {
        $this->merchantProductOfferFacade = $merchantProductOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
    ): ProductOfferCollectionTransfer {
        return $this->merchantProductOfferFacade->getProductOfferCollection($merchantProductOfferCriteriaTransfer);
    }
}
