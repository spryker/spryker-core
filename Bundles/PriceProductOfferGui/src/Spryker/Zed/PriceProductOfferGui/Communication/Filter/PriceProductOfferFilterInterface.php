<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Filter;

interface PriceProductOfferFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterOutProductOfferPrices(array $priceProductTransfers): array;
}
