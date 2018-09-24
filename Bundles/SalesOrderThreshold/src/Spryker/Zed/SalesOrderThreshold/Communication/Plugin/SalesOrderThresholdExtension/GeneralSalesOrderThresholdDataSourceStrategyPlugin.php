<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\SalesOrderThresholdExtension;

use Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface;
use Traversable;

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
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): array
    {
        $this->assertRequiredAttributes($salesOrderThresholdQuoteTransfer);

        $itemsSubTotal = $this->getThresholdItemsSubtotal(
            $salesOrderThresholdQuoteTransfer->getThresholdItems(),
            $salesOrderThresholdQuoteTransfer->getOriginalQuote()->getPriceMode()
        );

        if (!$itemsSubTotal) {
            return [];
        }

        $salesOrderThresholdTransfers = $this->getFacade()
            ->getSalesOrderThresholds(
                $salesOrderThresholdQuoteTransfer->getOriginalQuote()->getStore(),
                $salesOrderThresholdQuoteTransfer->getOriginalQuote()->getCurrency()
            );

        return array_map(function (SalesOrderThresholdTransfer $salesOrderThresholdTransfer) use ($itemsSubTotal) {
            $salesOrderThresholdTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue($itemsSubTotal);

            return $salesOrderThresholdTransfer;
        }, $salesOrderThresholdTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): void
    {
        $salesOrderThresholdQuoteTransfer
            ->requireThresholdItems()
            ->requireOriginalQuote()
            ->getOriginalQuote()
            ->requireStore()
            ->requireCurrency();
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $quotePriceMode
     *
     * @return int
     */
    protected function getThresholdItemsSubtotal(Traversable $itemTransfers, string $quotePriceMode): int
    {
        $itemsSubTotal = 0;
        foreach ($itemTransfers as $itemTransfer) {
            if ($quotePriceMode === SalesOrderThresholdConfig::PRICE_MODE_NET) {
                $itemsSubTotal += ($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
                continue;
            }

            $itemsSubTotal += ($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
        }

        return $itemsSubTotal;
    }
}
