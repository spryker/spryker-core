<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface ProductConcreteResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[] Keys are product SKUs
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteWithProductAbstractBySku(string $sku): ?ProductConcreteTransfer;
}
