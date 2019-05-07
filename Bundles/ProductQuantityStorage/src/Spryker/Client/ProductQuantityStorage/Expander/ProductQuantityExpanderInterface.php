<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(ProductViewTransfer $productViewTransfer): ProductViewTransfer;
}
