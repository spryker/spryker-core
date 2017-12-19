<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;
use Spryker\Client\ProductStorage\ProductStorageFactory;

/**
 * @method ProductStorageFactory getFactory()
 */
class ProductViewVariantExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $locale)
    {
        return $this->getFactory()
            ->createVariantExpander()
            ->expandProductVariantData($productViewTransfer, $locale);
    }
}
