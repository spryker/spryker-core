<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductQuantityValidator implements ProductQuantityValidatorInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface
     */
    protected $productQuantityResolver;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     * @param \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface $productQuantityResolver
     */
    public function __construct(
        ProductQuantityStorageReaderInterface $productQuantityStorageReader,
        ProductQuantityResolverInterface $productQuantityResolver
    ) {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
        $this->productQuantityResolver = $productQuantityResolver;
    }

    /**
     * @param int $idProduct
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(int $idProduct, int $quantity): int
    {
        $productQuantityStorageTransfer = $this->productQuantityStorageReader->findProductQuantityStorage($idProduct);

        if (!$productQuantityStorageTransfer) {
            return $quantity;
        }

        return $this->productQuantityResolver->getNearestQuantity($productQuantityStorageTransfer, $quantity);
    }
}
