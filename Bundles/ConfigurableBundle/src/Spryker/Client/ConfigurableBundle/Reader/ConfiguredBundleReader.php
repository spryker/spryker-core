<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
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
            if ($itemTransfer->getConfiguredBundle()) {
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
        $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();

        $configuredBundleTransfer
            ->requireGroupKey()
            ->requireQuantity()
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid()
                ->requireName();

        $configuredBundleTransfer
            ->requireSlot()
            ->getSlot()
                ->requireUuid();

        if (!isset($configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()])) {
            $configuredBundleTransfer->setSlot(null);
            $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()] = $configuredBundleTransfer;
        }

        $configuredBundleTransfers[$configuredBundleTransfer->getGroupKey()]->addItem($itemTransfer);

        return $configuredBundleTransfers;
    }
}
