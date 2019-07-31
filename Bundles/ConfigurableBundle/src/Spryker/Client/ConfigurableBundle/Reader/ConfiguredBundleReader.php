<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ConfiguredBundleReader implements ConfiguredBundleReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer
     */
    public function getConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): ConfiguredBundleCollectionTransfer
    {
        $configuredBundleTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundleItem() && $itemTransfer->getConfiguredBundle()) {
                $configuredBundleTransfers = $this->mapConfiguredBundle($itemTransfer, $configuredBundleTransfers);
            }
        }

        return (new ConfiguredBundleCollectionTransfer())
            ->setConfiguredBundles(new ArrayObject(array_values($configuredBundleTransfers)));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer[] $configuredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer[]
     */
    protected function mapConfiguredBundle(ItemTransfer $itemTransfer, array $configuredBundleTransfers): array
    {
        $configuredBundleItemTransfer = $itemTransfer
            ->requireConfiguredBundleItem()
            ->getConfiguredBundleItem();

        $configuredBundleItemTransfer
            ->requireSlot()
            ->getSlot()
            ->requireUuid();

        $configuredBundleTransfer = $itemTransfer
            ->requireConfiguredBundle()
            ->getConfiguredBundle();

        $configuredBundleTransfer
            ->requireGroupKey()
            ->requireQuantity()
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid()
                ->requireName();

        if (!isset($configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()])) {
            $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()] = (new ConfiguredBundleTransfer())
                ->setTemplate($configuredBundleTransfer->getTemplate())
                ->setQuantity($configuredBundleTransfer->getQuantity());
        }

        $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()]->addItem($itemTransfer);

        return $configuredBundleTransfers;
    }
}
