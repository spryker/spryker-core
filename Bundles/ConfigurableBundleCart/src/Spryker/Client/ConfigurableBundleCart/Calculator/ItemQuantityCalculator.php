<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

class ItemQuantityCalculator implements ItemQuantityCalculatorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param int $configuredBundleQuantity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function changeQuantity(ArrayObject $itemTransfers, int $configuredBundleQuantity): ArrayObject
    {
        $itemTransfersToUpdate = new ArrayObject();

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->requireConfiguredBundleItem()
                ->getConfiguredBundleItem()
                    ->requireQuantityPerSlot();

            $itemTransferToUpdate = (new ItemTransfer())
                ->fromArray($itemTransfer->toArray(false))
                ->setQuantity($itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot() * $configuredBundleQuantity);

            $itemTransfersToUpdate->append($itemTransferToUpdate);
        }

        return $itemTransfersToUpdate;
    }
}
