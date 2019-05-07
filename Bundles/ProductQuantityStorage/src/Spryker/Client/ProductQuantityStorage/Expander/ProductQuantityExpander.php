<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductQuantityExpander implements ProductQuantityExpanderInterface
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    public function expandConcreteProductsWithQuantityRestrictions(array $productConcreteTransfers): array
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->expandProductConcreteTransferWithQuantityRestrictions($productConcreteTransfer);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransferWithQuantityRestrictions(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productQuantityStorageTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            $productConcreteTransfer->setMinQuantity(1);
            $productConcreteTransfer->setQuantityInterval(1);

            return $productConcreteTransfer;
        }

        $minQuantity = $productQuantityStorageTransfer->getQuantityMin() ?? 1;
        $maxQuantity = $productQuantityStorageTransfer->getQuantityMax();
        $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? 1;
        $productConcreteTransfer->setMinQuantity($minQuantity);
        $productConcreteTransfer->setMaxQuantity($maxQuantity);
        $productConcreteTransfer->setQuantityInterval($quantityInterval);

        return $productConcreteTransfer;
    }
}
