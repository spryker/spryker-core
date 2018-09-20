<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\QuickOrderProductAdditionalData;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class QuickOrderProductAdditionalDataTransferExpander implements QuickOrderProductAdditionalDataTransferExpanderInterface
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
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer
     */
    public function expandQuickOrderProductAdditionalDataTransferWithQuantityRestrictions(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): QuickOrderProductAdditionalDataTransfer
    {
        if (!$quickOrderProductAdditionalDataTransfer->getIdProductConcrete()) {
            return $quickOrderProductAdditionalDataTransfer;
        }

        $productQuantityStorageTransfer = $this->productQuantityStorageReader->findProductQuantityStorage(
            $quickOrderProductAdditionalDataTransfer->getIdProductConcrete()
        );

        if ($productQuantityStorageTransfer !== null) {
            $quickOrderProductAdditionalDataTransfer->setProductQuantityStorage($productQuantityStorageTransfer);
        }

        return $quickOrderProductAdditionalDataTransfer;
    }
}
