<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductStockCodeSelector;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductStockCodeSelector implements ProductStockCodeSelectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function selectAppropriateCode(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $number = $this->selectEanOrSku($productConcreteTransfer);

        if ($number) {
            return $number;
        }

        return $this->resolveWithQueryContainer($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return null|string
     */
    protected function selectEanOrSku(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        if ($ean = $productConcreteTransfer->getEan()) {
            return $ean;
        }

        if ($sku = $productConcreteTransfer->getSku()) {
            return $sku;
        }

        return null;
    }

    /**
     * TODO: Communication with ProductQueryContainer should be provided using Bridge
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    protected function resolveWithQueryContainer(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $id = $productConcreteTransfer->getIdProductConcrete();

        // other logic goes there - TODO

        return $id;
    }
}
