<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Resolver;

use Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounderInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductQuantityResolver implements ProductQuantityResolverInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounderInterface
     */
    protected $productQuantityRounder;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     * @param \Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounderInterface $productQuantityRounder
     */
    public function __construct(
        ProductQuantityStorageReaderInterface $productQuantityStorageReader,
        ProductQuantityRounderInterface $productQuantityRounder
    ) {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
        $this->productQuantityRounder = $productQuantityRounder;
    }

    /**
     * @param int $idProduct
     * @param float $quantity
     *
     * @return float
     */
    public function getNearestQuantity(int $idProduct, float $quantity): float
    {
        $productQuantityStorageTransfer = $this->productQuantityStorageReader->findProductQuantityStorage($idProduct);

        if (!$productQuantityStorageTransfer) {
            return $quantity;
        }

        return $this->productQuantityRounder->getNearestQuantity($productQuantityStorageTransfer, $quantity);
    }
}
