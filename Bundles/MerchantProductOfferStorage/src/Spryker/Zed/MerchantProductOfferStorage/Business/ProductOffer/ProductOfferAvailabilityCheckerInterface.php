<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferAvailabilityCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailable(ProductOfferTransfer $productOfferTransfer): bool;
}
