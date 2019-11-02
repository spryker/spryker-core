<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface MerchantProductOfferFacadeInterface
{
    /**
     * Specification:
     * - This method looks for merchant data by provided offer reference.
     *
     * @api
     *
     * @param string $offerReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantByOfferReference(string $offerReference): ?ProductOfferTransfer;
}
