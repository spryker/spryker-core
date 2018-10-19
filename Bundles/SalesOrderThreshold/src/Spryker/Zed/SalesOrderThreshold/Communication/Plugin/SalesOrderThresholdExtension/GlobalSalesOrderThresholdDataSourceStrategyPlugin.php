<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\SalesOrderThresholdExtension;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
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
        if (empty($quoteTransfer->getItems())) {
            return [];
        }

        $this->assertRequiredAttributes($quoteTransfer);
        $salesOrderThresholdTransfers = $this->getFacade()
            ->getSalesOrderThresholds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency()
            );

        return array_map(function (SalesOrderThresholdTransfer $salesOrderThresholdTransfer) use ($quoteTransfer) {
            $salesOrderThresholdTransfer = $salesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue($quoteTransfer->getTotals()->getSubtotal());

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
        $quoteTransfer
            ->requireStore()
            ->requireCurrency()
            ->requireTotals()
            ->getTotals()
                ->requireSubtotal();
    }
}
