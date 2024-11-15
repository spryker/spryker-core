<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithMerchantProductOffer = $this->extractItemsWithMerchantProductOffer($cartReorderTransfer->getOrderItems());
        if ($itemsWithMerchantProductOffer === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithMerchantProductOffer as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $reorderItemTransfer->setProductOfferReference($itemTransfer->getProductOfferReferenceOrFail());
            $reorderItemTransfer->setMerchantReference($itemTransfer->getMerchantReferenceOrFail());
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsWithMerchantProductOffer(ArrayObject $itemTransfers): array
    {
        $itemsWithProductOffer = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getProductOfferReference() && $itemTransfer->getMerchantReference()) {
                $itemsWithProductOffer[$index] = $itemTransfer;
            }
        }

        return $itemsWithProductOffer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrder(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $index
     *
     * @return void
     */
    protected function addReorderItem(CartReorderTransfer $cartReorderTransfer, ItemTransfer $itemTransfer, int $index): void
    {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setMerchantReference($itemTransfer->getMerchantReferenceOrFail())
            ->setProductOfferReference($itemTransfer->getProductOfferReferenceOrFail());

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }
}
