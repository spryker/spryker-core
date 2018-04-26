<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\PostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnit\Communication\ProductMeasurementUnitCommunicationFactory getFactory()
 */
class QuantityProductMeasurementSalesUnitValuePostSavePlugin extends AbstractPlugin implements PostSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->setValue(
                $this->getFacade()->calculateQuantityNormalizedSalesUnitValue($itemTransfer)
            );
        }

        return $quoteTransfer;
    }
}
