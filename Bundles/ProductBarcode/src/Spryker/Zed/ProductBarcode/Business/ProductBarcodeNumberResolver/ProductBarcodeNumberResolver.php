<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductBarcodeNumberResolver;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;

class ProductBarcodeNumberResolver implements ProductBarcodeNumberResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainer $queryContainer
     *
     * @return string
     */
    public function resolve(ProductConcreteTransfer $productConcreteTransfer, ProductQueryContainer $queryContainer): string
    {
        if ($number = $this->resolveEanOrSku($productConcreteTransfer)) {
            return $number;
        }

        return $this->resolveWithQueryContainer($productConcreteTransfer, $queryContainer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return null|string
     */
    protected function resolveEanOrSku(ProductConcreteTransfer $productConcreteTransfer): ?string
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainer $queryContainer
     *
     * @return string
     */
    protected function resolveWithQueryContainer(ProductConcreteTransfer $productConcreteTransfer, ProductQueryContainer $queryContainer): string
    {
        $id = $productConcreteTransfer->getIdProductConcrete();

        // other logic goes there - TODO

        return $id;
    }
}
