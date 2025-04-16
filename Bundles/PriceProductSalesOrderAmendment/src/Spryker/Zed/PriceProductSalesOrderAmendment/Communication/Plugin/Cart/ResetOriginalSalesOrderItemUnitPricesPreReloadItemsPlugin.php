<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\Business\PriceProductSalesOrderAmendmentBusinessFactory getBusinessFactory()()
 */
class ResetOriginalSalesOrderItemUnitPricesPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Resets `QuoteTransfer.originalSalesOrderItemUnitPrices`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setOriginalSalesOrderItemUnitPrices([]);
    }
}
