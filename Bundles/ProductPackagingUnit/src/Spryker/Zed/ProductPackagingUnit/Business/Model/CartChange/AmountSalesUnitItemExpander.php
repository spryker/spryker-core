<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;

class AmountSalesUnitItemExpander implements AmountSalesUnitItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartWithAmountSalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }

            $this->expandItemWithAmountSalesUnit($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithAmountSalesUnit(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->getAmountSalesUnit()->requireIdProductMeasurementSalesUnit();

        $idProductMeasurementSalesUnit = $itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit();

        $productMeasurementUnitTransfer = $this->productMeasurementUnitFacade
            ->getProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);

        $itemTransfer->setAmountSalesUnit($productMeasurementUnitTransfer);

        return $itemTransfer;
    }
}
