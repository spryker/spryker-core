<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductQuantityValidator implements ProductQuantityValidatorInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     */
    public function __construct(ProductQuantityStorageReaderInterface $productQuantityStorageReader)
    {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
    }

    /**
     * @param int $idProduct
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(int $idProduct, int $quantity): int
    {
        $productQuantityTransfer = $this->productQuantityStorageReader->findProductQuantityStorage($idProduct);

        if (!$productQuantityTransfer) {
            return $quantity;
        }

        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $min;
        }

        if ($max && $quantity > $max) {
            $quantity = $max;
        }

        if ($interval && ($quantity - $min) % $interval !== 0) {
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
