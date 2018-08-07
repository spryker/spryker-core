<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\DataSource;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface;

class ThresholdDataSourceStrategy implements ThresholdDataSourceStrategyInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected $minimumOrderValueDataSourceStrategyPlugins;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface
     */
    protected $storeThresholdReader;

    /**
     * @param \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[] $minimumOrderValueDataSourceStrategyPlugins
     * @param \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface $storeThresholdReader
     */
    public function __construct(
        array $minimumOrderValueDataSourceStrategyPlugins,
        GlobalThresholdReaderInterface $storeThresholdReader
    ) {
        $this->minimumOrderValueDataSourceStrategyPlugins = $minimumOrderValueDataSourceStrategyPlugins;
        $this->storeThresholdReader = $storeThresholdReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        foreach ($this->minimumOrderValueDataSourceStrategyPlugins as $minimumOrderValueDataSourceStrategyPlugin) {
            $minimumOrderValueTransfers = $minimumOrderValueDataSourceStrategyPlugin->findApplicableThresholds($quoteTransfer);

            if (!empty($minimumOrderValueTransfers)) {
                return $minimumOrderValueTransfers;
            }
        }

        return $this->findGlobalApplicableThresholds($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function findGlobalApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $quoteTransfer
            ->requireTotals()
            ->requireStore()
            ->requireCurrency();

        $globalMinimumOrderValueTransfers = $this->storeThresholdReader
            ->getGlobalThresholdsByStoreAndCurrency($quoteTransfer->getStore(), $quoteTransfer->getCurrency());

        $cartSubTotal = $quoteTransfer->getTotals()->getSubtotal();

        return array_map(function (GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer) use ($cartSubTotal) {
            $minimumOrderValueTransfer = $globalMinimumOrderValueTransfer->getMinimumOrderValue();
            $minimumOrderValueTransfer->setSubTotal($cartSubTotal);

            return $minimumOrderValueTransfer;
        }, $globalMinimumOrderValueTransfers);
    }
}
