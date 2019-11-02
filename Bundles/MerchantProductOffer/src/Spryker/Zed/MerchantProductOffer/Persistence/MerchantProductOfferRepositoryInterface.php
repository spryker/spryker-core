<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface MerchantProductOfferRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantProductOfferByOfferReference(string $productOfferReference): ?ProductOfferTransfer;
}
