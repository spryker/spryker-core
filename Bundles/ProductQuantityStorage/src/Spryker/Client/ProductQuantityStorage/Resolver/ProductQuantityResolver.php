<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Resolver;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

class ProductQuantityResolver implements ProductQuantityResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(ProductQuantityStorageTransfer $productQuantityStorageTransfer, int $quantity): int
    {
        $min = $productQuantityStorageTransfer->getQuantityMin() ?: 1;
        $max = $productQuantityStorageTransfer->getQuantityMax();
        $interval = $productQuantityStorageTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $min;
        }

        if ($max && $quantity > $max) {
            $quantity = $max;
        }

        if ($interval && ($quantity - $min) % $interval !== 0) {
            $max = $max ?? ($quantity + $interval);

            $allowedQuantities = array_reverse(range($min, $max, $interval));
            $quantity = $this->getNearestQuantityFromAllowed($quantity, $allowedQuantities);
        }

        return $quantity;
    }

    /**
     * @param int $quantity
     * @param int[] $allowedQuantities
     *
     * @return int
     */
    protected function getNearestQuantityFromAllowed(int $quantity, array $allowedQuantities): int
    {
        $nearest = null;

        foreach ($allowedQuantities as $allowedQuantity) {
            if ($nearest === null || abs($quantity - $nearest) > abs($allowedQuantity - $quantity)) {
                $nearest = $allowedQuantity;
            }
        }

        return $nearest ?? $quantity;
    }
}
