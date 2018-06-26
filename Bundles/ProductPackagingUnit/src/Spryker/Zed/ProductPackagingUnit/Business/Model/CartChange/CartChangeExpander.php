<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;

class CartChangeExpander implements CartChangeExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $productPackagingUnitReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $productPackagingUnitReader
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $productPackagingUnitReader
    ) {
        $this->productPackagingUnitReader = $productPackagingUnitReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandWithQuantityPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantityPackagingUnit()) {
                continue;
            }

            $itemTransfer->getQuantityPackagingUnit()->requireStockAmount();

            $this->expandItem($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItem(ItemTransfer $itemTransfer)
    {
        $productPackagingUnitTransfer = $this->productPackagingUnitReader
            ->getProductPackagingUnitBySku(
                $itemTransfer->getSku()
            );

        $productPackagingLeadProductTransfer = $this->productPackagingUnitReader
            ->getProductPackagingLeadProductByProductPackagingSku(
                $itemTransfer->getSku()
            );

        $quantityPackagingUnit = $itemTransfer->getQuantityPackagingUnit();
        $quantityPackagingUnit
            ->setProductPackagingUnit($productPackagingUnitTransfer)
            ->setProductPackagingUnitLeadProduct($productPackagingLeadProductTransfer);

        $itemTransfer->setQuantityPackagingUnit(
            $quantityPackagingUnit
        );

        return $itemTransfer;
    }
}
