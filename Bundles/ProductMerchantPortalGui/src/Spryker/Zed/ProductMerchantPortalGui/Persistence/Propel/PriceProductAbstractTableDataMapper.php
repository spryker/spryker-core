<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;

class PriceProductAbstractTableDataMapper
{
    /**
     * @param mixed[] $priceProductAbstractTableDataArray
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer $priceProductAbstractTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductAbstractTableViewCollectionTransfer
     */
    public function mapPriceProductAbstractTableDataArrayToPriceProductAbstractTableViewCollectionTransfer(
        array $priceProductAbstractTableDataArray,
        PriceProductAbstractTableViewCollectionTransfer $priceProductAbstractTableViewCollectionTransfer
    ): PriceProductAbstractTableViewCollectionTransfer {
        $priceProductAbstractTableViewTransfers = [];

        foreach ($priceProductAbstractTableDataArray as $priceProductAbstractTableRowDataArray) {
            $priceProductAbstractTableViewTransfer = (new PriceProductAbstractTableViewTransfer())
                ->fromArray($priceProductAbstractTableRowDataArray, true);

            $priceProductAbstractTableViewTransfers[] = $priceProductAbstractTableViewTransfer;
        }

        $priceProductAbstractTableViewCollectionTransfer->setPriceProductAbstractTableViews(
            new ArrayObject($priceProductAbstractTableViewTransfers)
        );

        return $priceProductAbstractTableViewCollectionTransfer;
    }
}
