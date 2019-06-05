<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransfersWithQuantityRestrictions(array $productConcreteTransfers): array
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
    public function expandProductConcreteTransferWithQuantityRestrictions(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        [
            $quantityMin,
            $quantityMax,
            $quantityInterval,
        ] = $this->prepareQuantityRestrictionDataByProductConcreteId($productConcreteTransfer->getIdProductConcrete());

        return $productConcreteTransfer
            ->setMinQuantity($quantityMin)
            ->setMaxQuantity($quantityMax)
            ->setQuantityInterval($quantityInterval);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        [
            $quantityMin,
            $quantityMax,
            $quantityInterval,
        ] = $this->prepareQuantityRestrictionDataByProductConcreteId($productViewTransfer->getIdProductConcrete());

        return $productViewTransfer
            ->setQuantityMin($quantityMin)
            ->setQuantityMax($quantityMax)
            ->setQuantityInterval($quantityInterval);
    }

    /**
     * @param int $productConcreteId
     *
     * @return array
     */
    protected function prepareQuantityRestrictionDataByProductConcreteId(int $productConcreteId): array
    {
        $quantityMin = 1;
        $quantityInterval = 1;
        $quantityMax = null;

        $productQuantityStorageTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($productConcreteId);

        if ($productQuantityStorageTransfer !== null) {
            $quantityMin = $productQuantityStorageTransfer->getQuantityMin() ?? 1;
            $quantityMax = $productQuantityStorageTransfer->getQuantityMax();
            $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? 1;
        }

        return [$quantityMin, $quantityMax, $quantityInterval];
    }
}
