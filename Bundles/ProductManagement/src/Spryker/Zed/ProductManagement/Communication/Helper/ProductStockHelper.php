<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;

class ProductStockHelper implements ProductStockHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockTypeEntities
     *
     * @return void
     */
    public function addMissingStockTypes(ProductConcreteTransfer $productConcreteTransfer, array $stockTypeEntities)
    {
        foreach ($stockTypeEntities as $type) {
            if ($this->stockTypeExist($productConcreteTransfer, $type->getName())) {
                continue;
            }
            $stockProductTransfer = new StockProductTransfer();
            $stockProductTransfer->setStockType($type->getName());
            $stockProductTransfer->setQuantity(0);

            $productConcreteTransfer->addStock($stockProductTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string $type
     *
     * @return bool
     */
    protected function stockTypeExist(ProductConcreteTransfer $productConcreteTransfer, $type)
    {
        foreach ($productConcreteTransfer->getStocks() as $stockProduct) {
            if ($stockProduct->getStockType() === $type) {
                return true;
            }
        }

        return false;
    }
}
