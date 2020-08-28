<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferReader;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

interface MerchantProductOfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getMerchantProductOfferCollection(
        MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
    ): ProductOfferCollectionTransfer;
}
