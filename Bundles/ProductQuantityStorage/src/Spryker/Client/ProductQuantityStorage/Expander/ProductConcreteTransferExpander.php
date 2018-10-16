<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductConcreteTransferExpander implements ProductConcreteTransferExpanderInterface
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
    public function expandWithProductQuantity(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer->requireIdProductConcrete();

        $productQuantityStorageTransfer = $this->productQuantityStorageReader->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());
        if ($productQuantityStorageTransfer === null) {
            return $productConcreteTransfer;
        }

        $productQuantityTransfer = $this->mapProductQuantityStorageTransferToProductQuantityTransfer(
            $productQuantityStorageTransfer,
            new ProductQuantityTransfer()
        );
        $productConcreteTransfer->setProductQuantity($productQuantityTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function mapProductQuantityStorageTransferToProductQuantityTransfer(
        ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        ProductQuantityTransfer $productQuantityTransfer
    ) {
        $productQuantityTransfer->fromArray(
            $productQuantityStorageTransfer->modifiedToArray(),
            true
        );

        return $productQuantityTransfer;
    }
}
