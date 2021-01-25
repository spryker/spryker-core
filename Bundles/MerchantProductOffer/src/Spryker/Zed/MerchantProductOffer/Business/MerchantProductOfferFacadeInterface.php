<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

interface MerchantProductOfferFacadeInterface
{
    /**
     * Specification:
     *  - Gets ProductOfferCollectionTransfer filtered by MerchantProductOfferCriteriaFilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(
        MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
    ): ProductOfferCollectionTransfer;
}
