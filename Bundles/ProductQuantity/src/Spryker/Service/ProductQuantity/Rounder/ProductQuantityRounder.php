<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity\Rounder;

use Generated\Shared\Transfer\ProductQuantityTransfer;

class ProductQuantityRounder implements ProductQuantityRounderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(ProductQuantityTransfer $productQuantityTransfer, int $quantity): int
    {
        $min = $productQuantityTransfer->getQuantityMin() ?: 1;
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $min;
        }

        if ($max && $quantity > $max) {
            $quantity = $max;
        }

        if ($interval && ($quantity - $min) % $interval !== 0) {
            $max = $max ?? ($quantity + $interval);

            $quantity = $this->getNearestAllowedQuantity(
                $quantity,
                $this->getAllowedQuantities($min, $max, $interval, $quantity)
            );
        }

        return $quantity;
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $interval
     * @param int $quantity
     *
     * @return int[]
     */
    protected function getAllowedQuantities(int $min, int $max, int $interval, int $quantity): array
    {
        if ($quantity - $interval > $min) {
            $min = (int)round(($quantity - $interval) / $interval) * $interval + $min;
        }

        if ($min + $interval > $max) {
            return [$min];
        }

        return array_reverse(range($min, $max, $interval));
    }

    /**
     * @param int $quantity
     * @param int[] $allowedQuantities
     *
     * @return int
     */
    protected function getNearestAllowedQuantity(int $quantity, array $allowedQuantities): int
    {
        if (count($allowedQuantities) === 1) {
            return reset($allowedQuantities);
        }

        $nearest = null;

        foreach ($allowedQuantities as $allowedQuantity) {
            if ($nearest === null || abs($quantity - $nearest) > abs($allowedQuantity - $quantity)) {
                $nearest = $allowedQuantity;
            }
        }

        return $nearest ?? $quantity;
    }
}
