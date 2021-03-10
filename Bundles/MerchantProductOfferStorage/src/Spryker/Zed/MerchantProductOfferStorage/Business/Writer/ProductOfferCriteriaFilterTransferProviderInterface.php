<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;

interface ProductOfferCriteriaFilterTransferProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function createProductOfferCriteriaFilterTransfer(): ProductOfferCriteriaFilterTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function createIncorrectProductOfferCriteriaFilterTransfer(): ProductOfferCriteriaFilterTransfer;
}
