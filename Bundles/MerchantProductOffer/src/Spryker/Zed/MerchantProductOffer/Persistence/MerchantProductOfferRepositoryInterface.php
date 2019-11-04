<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

interface MerchantProductOfferRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return int|null
     */
    public function findIdMerchantByProductOfferReference(string $productOfferReference): ?int;
}
