<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;

class ProductStockHelper implements ProductStockHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function trimProductAbstractAvailabilityQuantities(ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer): ProductAbstractAvailabilityTransfer
    {
        $productAbstractAvailabilityTransfer = $this->trimStockQuantityValue($productAbstractAvailabilityTransfer);
        $productAbstractAvailabilityTransfer = $this->trimReservationQuantityValue($productAbstractAvailabilityTransfer);

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function trimStockQuantityValue(ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer): ProductAbstractAvailabilityTransfer
    {
        if ($productAbstractAvailabilityTransfer->getStockQuantity() !== null) {
            $productAbstractAvailabilityTransfer->setStockQuantity(
                $productAbstractAvailabilityTransfer->getStockQuantity()->trim()
            );
        }

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function trimReservationQuantityValue(ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer): ProductAbstractAvailabilityTransfer
    {
        if ($productAbstractAvailabilityTransfer->getReservationQuantity() !== null) {
            $productAbstractAvailabilityTransfer->setReservationQuantity(
                $productAbstractAvailabilityTransfer->getReservationQuantity()->trim()
            );
        }

        return $productAbstractAvailabilityTransfer;
    }
}
