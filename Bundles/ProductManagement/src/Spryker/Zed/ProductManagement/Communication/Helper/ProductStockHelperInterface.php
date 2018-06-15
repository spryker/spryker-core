<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductStockHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockTypeEntities
     *
     * @return void
     */
    public function addMissingStockTypes(ProductConcreteTransfer $productConcreteTransfer, array $stockTypeEntities);
}
