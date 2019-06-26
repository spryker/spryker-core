<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
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
    public function __construct(
        ProductQuantityStorageReaderInterface $productQuantityStorageReader
    ) {
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
        $productQuantityStorageTransfer = $this->prepareQuantityRestrictionDataByProductConcreteId($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            return $productConcreteTransfer;
        }

        return $productConcreteTransfer
            ->setMinQuantity($productQuantityStorageTransfer->getQuantityMin())
            ->setMaxQuantity($productQuantityStorageTransfer->getQuantityMax())
            ->setQuantityInterval($productQuantityStorageTransfer->getQuantityInterval());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        $productQuantityStorageTransfer = $this->prepareQuantityRestrictionDataByProductConcreteId($productViewTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            return $productViewTransfer;
        }

        return $productViewTransfer
            ->setQuantityMin($productQuantityStorageTransfer->getQuantityMin())
            ->setQuantityMax($productQuantityStorageTransfer->getQuantityMax())
            ->setQuantityInterval($productQuantityStorageTransfer->getQuantityInterval());
    }

    /**
     * @param int|null $productConcreteId
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    protected function prepareQuantityRestrictionDataByProductConcreteId(?int $productConcreteId): ?ProductQuantityStorageTransfer
    {
        if ($productConcreteId === null) {
            return null;
        }

        return $this->productQuantityStorageReader
            ->getProductQuantityStorage($productConcreteId);
    }
}
