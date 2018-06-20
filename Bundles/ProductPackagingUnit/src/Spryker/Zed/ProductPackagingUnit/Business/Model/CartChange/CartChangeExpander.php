<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
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

            $itemTransfer->getQuantityPackagingUnit()->requireIdProductPackagingUnit();

            $productPackagingUnitTransfer = $this->productPackagingUnitReader
                ->getProductPackagingUnitById(
                    $itemTransfer->getQuantityPackagingUnit()->getIdProductPackagingUnit()
                );

            $this->expandWithAmount($productPackagingUnitTransfer, $itemTransfer);

            $itemTransfer->setQuantityPackagingUnit($productPackagingUnitTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTransfer $productPackagingUnitTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer
     */
    protected function expandWithAmount(ProductPackagingUnitTransfer $productPackagingUnitTransfer, ItemTransfer $itemTransfer)
    {
        $itemAmount = $productPackagingUnitTransfer->getProductPackagingUnitAmount()->getDefaultAmount() * $itemTransfer->getQuantity();
        $productPackagingUnitTransfer->getProductPackagingUnitAmount()->setAmount($itemAmount);

        return $productPackagingUnitTransfer;
    }
}
