<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductQuantityExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    public function expandConcreteProductsWithQuantityRestrictions(array $productConcreteTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransferWithQuantityRestrictions(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
