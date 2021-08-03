<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;

interface PriceProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProducts(PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer): array;
}
