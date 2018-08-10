<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\DataSource;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig;

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
     * @var \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[] $minimumOrderValueDataSourceStrategyPlugins
     * @param \Spryker\Zed\MinimumOrderValue\Business\GlobalThreshold\GlobalThresholdReaderInterface $storeThresholdReader
     * @param \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig $config
     */
    public function __construct(
        array $minimumOrderValueDataSourceStrategyPlugins,
        GlobalThresholdReaderInterface $storeThresholdReader,
        MinimumOrderValueConfig $config
    ) {
        $this->minimumOrderValueDataSourceStrategyPlugins = $minimumOrderValueDataSourceStrategyPlugins;
        $this->storeThresholdReader = $storeThresholdReader;
        $this->config = $config;
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
            ->requireStore()
            ->requireCurrency();

        $globalMinimumOrderValueTransfers = $this->storeThresholdReader
            ->getGlobalThresholdsByStoreAndCurrency($quoteTransfer->getStore(), $quoteTransfer->getCurrency());

        $cartSubTotal = $this->getCartSubtotal($quoteTransfer);

        return array_map(function (GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer) use ($cartSubTotal) {
            $minimumOrderValueTransfer = $globalMinimumOrderValueTransfer->getMinimumOrderValue();
            $minimumOrderValueTransfer->setSubTotal($cartSubTotal);

            return $minimumOrderValueTransfer;
        }, $globalMinimumOrderValueTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getCartSubtotal(QuoteTransfer $quoteTransfer): int
    {
        $cartSubTotal = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($quoteTransfer->getPriceMode() === $this->config->getNetPriceMode()) {
                $cartSubTotal += ($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
                continue;
            }

            $cartSubTotal += ($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
        }

        return $cartSubTotal;
    }
}
