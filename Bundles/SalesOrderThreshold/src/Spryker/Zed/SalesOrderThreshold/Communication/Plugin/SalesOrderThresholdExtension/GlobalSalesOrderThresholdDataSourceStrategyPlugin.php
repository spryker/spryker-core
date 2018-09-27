<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\SalesOrderThresholdExtension;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 */
class GlobalSalesOrderThresholdDataSourceStrategyPlugin extends AbstractPlugin implements SalesOrderThresholdDataSourceStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     * - Finds the applicable global store and currency thresholds for the cart sub total.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $this->assertRequiredAttributes($quoteTransfer);

        $cartSubTotal = $this->getThresholdCartSubtotal($quoteTransfer);

        if (!$cartSubTotal) {
            return [];
        }

        $salesOrderThresholdTransfers = $this->getFacade()
            ->getSalesOrderThresholds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency()
            );

        return array_map(function (SalesOrderThresholdTransfer $salesOrderThresholdTransfer) use ($cartSubTotal) {
            $salesOrderThresholdTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue($cartSubTotal);

            return $salesOrderThresholdTransfer;
        }, $salesOrderThresholdTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requireStore()->requireCurrency();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getThresholdCartSubtotal(QuoteTransfer $quoteTransfer): int
    {
        $cartSubTotal = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($quoteTransfer->getPriceMode() === SalesOrderThresholdConfig::PRICE_MODE_NET) {
                $cartSubTotal += ($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
                continue;
            }

            $cartSubTotal += ($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
        }

        return $cartSubTotal;
    }
}
