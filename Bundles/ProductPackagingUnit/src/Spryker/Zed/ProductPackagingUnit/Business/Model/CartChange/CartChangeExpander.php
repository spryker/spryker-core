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
    public function expandWithAmountLeadProduct(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount() || !$itemTransfer->getAmountSalesUnit()) {
                continue;
            }

            $this->expandItem($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer = $this->expandItemWithLeadProduct($itemTransfer);
        $itemTransfer = $this->expandItemWithProductPackagingUnit($itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithLeadProduct(ItemTransfer $itemTransfer)
    {
        $productPackagingLeadProductTransfer = $this->productPackagingUnitReader
            ->findProductPackagingLeadProductByProductPackagingSku($itemTransfer->getSku());

        if ($productPackagingLeadProductTransfer) {
            $itemTransfer->setAmountLeadProduct($productPackagingLeadProductTransfer);
        }

        $productMeasurementUnitTransfer = $this->productPackagingUnitReader
            ->getProductMeasurementSalesUnitTransfer($itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit());

        $itemTransfer->setAmountSalesUnit($productMeasurementUnitTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithProductPackagingUnit(ItemTransfer $itemTransfer)
    {
        $productPackagingUnitTransfer = $this->productPackagingUnitReader
            ->findProductPackagingUnitByProductId($itemTransfer->getId());

        if ($productPackagingUnitTransfer) {
            $itemTransfer->setProductPackagingUnit($productPackagingUnitTransfer);
        }

        return $itemTransfer;
    }
}
