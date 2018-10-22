<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\Plugin\ProductStorageExtension;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductListStorage\ProductListStorageFactory getFactory()
 */
class ProductViewVariantRestrictionPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * @deprecated Use it only if the version you have of `ProductStorage` doesn't support filtering restricted products.
     *
     * {@inheritdoc}
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
            ->createProductViewVariantRestrictionExpander()
            ->expandProductVariantData($productViewTransfer);
    }
}
