<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

interface MerchantProductOfferFacadeInterface
{
    /**
     * Specification:
     * - This method looks for id merchant by provided offer reference.
     *
     * @api
     *
     * @param string $productOfferReference
     *
     * @return int|null
     */
    public function findIdMerchantByProductOfferReference(string $productOfferReference): ?int;
}
