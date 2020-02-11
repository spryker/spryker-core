<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductOfferRepositoryInterface
{
    /**
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductTransfers(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array;
}
