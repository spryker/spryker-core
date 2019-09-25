<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class ProductViewPriceExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds price value based on current store settings.
     * - Adds prices key-value array of available price types (e.g. DEFAULT, ORIGINAL).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName)
    {
        return $this->getFactory()
            ->createProductViewPriceExpander()
            ->expandProductViewPriceData($productViewTransfer);
    }
}
