<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Price;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;

interface ValuePriceReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function resolveProductAbstractOptionStorageTransferProductOptionValuePrices(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
    ): ProductAbstractOptionStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function resolveProductAbstractOptionStorageTransfersProductOptionValuePrices(
        array $productAbstractOptionStorageTransfers
    ): array;
}
