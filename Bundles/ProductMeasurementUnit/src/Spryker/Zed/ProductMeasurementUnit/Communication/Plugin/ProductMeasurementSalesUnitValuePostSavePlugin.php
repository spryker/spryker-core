<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\PostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnit\Communication\ProductMeasurementUnitCommunicationFactory getFactory()
 */
class ProductMeasurementSalesUnitValuePostSavePlugin extends AbstractPlugin implements PostSavePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->setValue(
                round($itemTransfer->getQuantity() * $itemTransfer->getQuantitySalesUnit()->getConversion() * $itemTransfer->getQuantitySalesUnit()->getPrecision())
            );
        }

        return $quoteTransfer;
    }
}
