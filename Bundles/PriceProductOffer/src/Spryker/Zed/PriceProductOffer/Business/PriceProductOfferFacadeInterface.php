<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business;

interface PriceProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Returns list of product offer prices.
     *
     * @api
     *
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductOfferTransfers(array $skus): array;
}
