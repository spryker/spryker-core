<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\ProductConcreteExpander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader \
     */
    public function __construct(ProductQuantityStorageReaderInterface $productQuantityStorageReader)
    {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransferWithQuantityRestrictions(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        if (!$productConcreteTransfer->getIdProductConcrete()) {
            return $productConcreteTransfer;
        }

        $productQuantityStorageTransfer = $this->productQuantityStorageReader->findProductQuantityStorage(
            $productConcreteTransfer->getIdProductConcrete()
        );

        if ($productQuantityStorageTransfer !== null) {
            $productConcreteTransfer->setProductQuantity($productQuantityStorageTransfer);
        }

        return $productConcreteTransfer;
    }
}
