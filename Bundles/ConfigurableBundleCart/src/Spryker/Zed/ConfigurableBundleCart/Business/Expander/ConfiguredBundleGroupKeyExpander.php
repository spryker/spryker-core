<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ConfiguredBundleGroupKeyExpander implements ConfiguredBundleGroupKeyExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isExpandWithWithGroupKeyNeeded($itemTransfer)) {
                continue;
            }

            $this->expandItemTransferWithGroupKey($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isExpandWithWithGroupKeyNeeded(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getConfiguredBundle() && $itemTransfer->getConfiguredBundleItem();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItemTransferWithGroupKey(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireGroupKey()
            ->requireConfiguredBundle()
            ->getConfiguredBundle()
                ->requireGroupKey();

        $itemTransfer->setGroupKey(
            sprintf(
                '%s-%s',
                $itemTransfer->getConfiguredBundle()->getGroupKey(),
                $itemTransfer->getGroupKey()
            )
        );
    }
}
