<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface;

class ProductOfferAvailabilityChecker implements ProductOfferAvailabilityCheckerInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface
     */
    protected $merchantProductOfferStorageToAvailabilityFacade;

    /**
     * ProductOfferAvailabilityChecker constructor.
     *
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface $merchantProductOfferStorageToAvailabilityFacade
     */
    public function __construct(
        MerchantProductOfferStorageToAvailabilityFacadeInterface $merchantProductOfferStorageToAvailabilityFacade
    ) {
        $this->merchantProductOfferStorageToAvailabilityFacade = $merchantProductOfferStorageToAvailabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailable(ProductOfferTransfer $productOfferTransfer): bool
    {
        return true; // TODO use facade
    }
}
