<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;

interface PriceProductTableViewSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    public function sortPriceProductTableViews(
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer,
        PriceProductTableCriteriaTransfer $criteriaTransfer
    ): PriceProductTableViewCollectionTransfer;
}
