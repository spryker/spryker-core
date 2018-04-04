<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit;

use Generated\Shared\Transfer\ItemTransfer;

class ProductMeasurementSalesUnitGroupKeyGenerator implements ProductMeasurementSalesUnitGroupKeyGeneratorInterface
{
    protected const GROUP_KEY_FORMAT = '%s_id_sales_unit_%s';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return string
     */
    public function expandItemGroupKey(ItemTransfer $item): string
    {
        if (!$item->getQuantitySalesUnit()) {
            return $item->getGroupKey();
        }

        return sprintf(
            static::GROUP_KEY_FORMAT,
            $item->getGroupKey(),
            $item->getQuantitySalesUnit()->getIdProductMeasurementSalesUnit()
        );
    }
}
