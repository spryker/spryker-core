<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductOptionCartConnector\Business\Messenger\ProductOptionMessengerInterface;
use Spryker\Zed\ProductOptionCartConnector\Persistence\ProductOptionCartConnectorRepositoryInterface;

class InactiveProductOptionItemsFilter implements InactiveProductOptionItemsFilterInterface
{
    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Persistence\ProductOptionCartConnectorRepositoryInterface $productOptionCartConnectorRepository
     * @param \Spryker\Zed\ProductOptionCartConnector\Business\Messenger\ProductOptionMessengerInterface $productOptionMessenger
     */
    public function __construct(
        protected ProductOptionCartConnectorRepositoryInterface $productOptionCartConnectorRepository,
        protected ProductOptionMessengerInterface $productOptionMessenger
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterOutInactiveProductOptionCartChangeItems(
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer {
        $productOptionValueIds = $this->getIndexedProductOptionValueIds($cartChangeTransfer);

        if ($productOptionValueIds === []) {
            return $cartChangeTransfer;
        }

        $activeProductOptionValueIds = $this->productOptionCartConnectorRepository
            ->filterProductOptionValueIdsByActiveGroup($productOptionValueIds);
        $indexedActiveProductOptionValueIds = array_combine($activeProductOptionValueIds, $activeProductOptionValueIds);
        $filteredItemTransfers = new ArrayObject();
        $messageTransfersIndexedBySku = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (
                $itemTransfer->getProductOptions()->count() === 0 ||
                $this->areAllProductOptionsActive($itemTransfer, $indexedActiveProductOptionValueIds)
            ) {
                $filteredItemTransfers->append($itemTransfer);

                continue;
            }

            $messageTransfersIndexedBySku = $this->productOptionMessenger->addInfoMessageInactiveProductOptionItemRemoved(
                $itemTransfer->getSkuOrFail(),
                $messageTransfersIndexedBySku,
            );
        }

        $cartChangeTransfer->setItems($filteredItemTransfers);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<int, int> $indexedActiveProductOptionValueIds
     *
     * @return bool
     */
    protected function areAllProductOptionsActive(
        ItemTransfer $itemTransfer,
        array $indexedActiveProductOptionValueIds
    ): bool {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if (!isset($indexedActiveProductOptionValueIds[$productOptionTransfer->getIdProductOptionValueOrFail()])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return list<int>
     */
    protected function getIndexedProductOptionValueIds(CartChangeTransfer $cartChangeTransfer): array
    {
        $productOptionValueIds = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductOptions()->count() === 0) {
                continue;
            }

            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $productOptionValueIds[] = $productOptionTransfer->getIdProductOptionValueOrFail();
            }
        }

        return array_unique($productOptionValueIds);
    }
}
