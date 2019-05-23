<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuantityRestrictionTransfer;
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
        $quantityRestrictionTransfer = $this->prepareQuantityRestrictionTransferByProductConcreteId($productConcreteTransfer->getIdProductConcrete());

        return $productConcreteTransfer
            ->setMinQuantity($quantityRestrictionTransfer->getQuantityMin())
            ->setMaxQuantity($quantityRestrictionTransfer->getQuantityMax())
            ->setQuantityInterval($quantityRestrictionTransfer->getQuantityInterval());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        $quantityRestrictionTransfer = $this->prepareQuantityRestrictionTransferByProductConcreteId($productViewTransfer->getIdProductConcrete());

        return $productViewTransfer
            ->setQuantityMin($quantityRestrictionTransfer->getQuantityMin())
            ->setQuantityMax($quantityRestrictionTransfer->getQuantityMax())
            ->setQuantityInterval($quantityRestrictionTransfer->getQuantityInterval());
    }

    /**
     * @param int $productConcreteId
     *
     * @return \Generated\Shared\Transfer\QuantityRestrictionTransfer
     */
    protected function prepareQuantityRestrictionTransferByProductConcreteId(int $productConcreteId): QuantityRestrictionTransfer
    {
        $quantityRestrictionTransfer = (new QuantityRestrictionTransfer())
            ->setQuantityMin(1)
            ->setQuantityInterval(1);

        $productQuantityStorageTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($productConcreteId);

        if ($productQuantityStorageTransfer !== null) {
            $quantityRestrictionTransfer
                ->setQuantityMin($productQuantityStorageTransfer->getQuantityMin())
                ->setQuantityMax($productQuantityStorageTransfer->getQuantityMax())
                ->setQuantityInterval($productQuantityStorageTransfer->getQuantityInterval());
        }

        return $quantityRestrictionTransfer;
    }
}
