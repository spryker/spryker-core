<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\Business\PriceProductSalesOrderAmendmentBusinessFactory getBusinessFactory()
 */
class ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.items` is not empty.
     * - Sets empty array to `QuoteTransfer.originalSalesOrderItemUnitPrices` if `QuoteTransfer.originalSalesOrderItemUnitPrices` is not empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getItems()->count() !== 0) {
            return $quoteTransfer;
        }

        if ($quoteTransfer->getOriginalSalesOrderItemUnitPrices()) {
            $quoteTransfer->setOriginalSalesOrderItemUnitPrices([]);
        }

        return $quoteTransfer;
    }
}
