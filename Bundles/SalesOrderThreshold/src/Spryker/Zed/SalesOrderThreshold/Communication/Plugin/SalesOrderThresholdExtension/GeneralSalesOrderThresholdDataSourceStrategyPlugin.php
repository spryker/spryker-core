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
class GeneralSalesOrderThresholdDataSourceStrategyPlugin extends AbstractPlugin implements SalesOrderThresholdDataSourceStrategyPluginInterface
{
    /**
     * {@inheritdoc}
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

        $itemsSubTotal = $this->getThresholdItemsSubtotal($quoteTransfer);

        if (!$itemsSubTotal) {
            return [];
        }

        $salesOrderThresholdTransfers = $this->getFacade()
            ->getSalesOrderThresholds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency()
            );

        return array_map(function (SalesOrderThresholdTransfer $salesOrderThresholdTransfer) use ($itemsSubTotal) {
            $salesOrderThresholdTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue($itemsSubTotal);

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
    protected function getThresholdItemsSubtotal(QuoteTransfer $quoteTransfer): int
    {
        $itemsSubTotal = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($quoteTransfer->getPriceMode() === SalesOrderThresholdConfig::PRICE_MODE_NET) {
                $itemsSubTotal += ($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
                continue;
            }

            $itemsSubTotal += ($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
        }

        return $itemsSubTotal;
    }
}
