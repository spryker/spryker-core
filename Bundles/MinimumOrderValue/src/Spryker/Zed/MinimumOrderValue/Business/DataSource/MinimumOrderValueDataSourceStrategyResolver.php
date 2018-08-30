<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\DataSource;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReaderInterface;

class MinimumOrderValueDataSourceStrategyResolver implements MinimumOrderValueDataSourceStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected $minimumOrderValueDataSourceStrategyPlugins;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReaderInterface
     */
    protected $minimumOrderValueReader;

    /**
     * @param \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[] $minimumOrderValueDataSourceStrategyPlugins
     * @param \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReaderInterface $minimumOrderValueReader
     */
    public function __construct(
        array $minimumOrderValueDataSourceStrategyPlugins,
        MinimumOrderValueReaderInterface $minimumOrderValueReader
    ) {
        $this->minimumOrderValueDataSourceStrategyPlugins = $minimumOrderValueDataSourceStrategyPlugins;
        $this->minimumOrderValueReader = $minimumOrderValueReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
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
     * @return \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer[]
     */
    public function findGlobalApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $quoteTransfer
            ->requireStore()
            ->requireCurrency();

        $minimumOrderValueTransfers = $this->minimumOrderValueReader
            ->findMinimumOrderValues($quoteTransfer->getStore(), $quoteTransfer->getCurrency());

        $cartSubTotal = $this->getCartSubtotal($quoteTransfer);

        return array_map(function (MinimumOrderValueTransfer $minimumOrderValueTransfer) use ($cartSubTotal) {
            $minimumOrderValueTransfer = $minimumOrderValueTransfer->getMinimumOrderValueThreshold();
            $minimumOrderValueTransfer->setValue($cartSubTotal);

            return $minimumOrderValueTransfer;
        }, $minimumOrderValueTransfers);
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
            if ($quoteTransfer->getPriceMode() === MinimumOrderValueConfig::PRICE_MODE_NET) {
                $cartSubTotal += ($itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity());
                continue;
            }

            $cartSubTotal += ($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity());
        }

        return $cartSubTotal;
    }
}
