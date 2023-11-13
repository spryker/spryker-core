<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Merger;

use Generated\Shared\Transfer\ItemTransfer;

class ItemMerger implements ItemMergerInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function mergeItemTransfersByCriteria(array $itemTransfers): array
    {
        $mergedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $itemKey = $this->generateItemKey($itemTransfer);
            if (isset($mergedItemTransfers[$itemKey])) {
                $mergedItemTransfers[$itemKey] = $this->mergeItemTransfers(
                    $mergedItemTransfers[$itemKey],
                    $itemTransfer,
                );

                continue;
            }

            $mergedItemTransfers[$itemKey] = (new ItemTransfer())->fromArray($itemTransfer->toArray());
        }

        return $mergedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $originalItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $mergedItemTransfer
     *
     * @return bool
     */
    public function isSameItemTransfer(ItemTransfer $originalItemTransfer, ItemTransfer $mergedItemTransfer): bool
    {
        return $this->generateItemKey($originalItemTransfer) === $this->generateItemKey($mergedItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $baseItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $mergeableItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mergeItemTransfers(ItemTransfer $baseItemTransfer, ItemTransfer $mergeableItemTransfer): ItemTransfer
    {
        $baseItemTransfer->setQuantity(
            $baseItemTransfer->getQuantityOrFail() + $mergeableItemTransfer->getQuantityOrFail(),
        );

        return $baseItemTransfer->setProductOfferReference(null);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function generateItemKey(ItemTransfer $itemTransfer): string
    {
        $itemKeyParts = [
            $itemTransfer->getSkuOrFail(),
            $itemTransfer->getMerchantReference(),
            $itemTransfer->getServicePoint() ? $itemTransfer->getServicePointOrFail()->getKey() : null,
        ];

        return implode('-', array_filter($itemKeyParts));
    }
}
