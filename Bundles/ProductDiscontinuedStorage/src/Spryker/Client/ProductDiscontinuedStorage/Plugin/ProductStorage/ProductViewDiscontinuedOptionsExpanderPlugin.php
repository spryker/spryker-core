<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class ProductViewDiscontinuedOptionsExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the product view super attributes and attribute variant map with discontinued postfix.
     * - Expands the product view attribute variant map attribute value with discontinued postfix when there are still
     *   attributes to be selected, attribute value is not in selected list, and {@link \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig::isOnlyDiscontinuedVariantAttributesPostfixEnabled()} is enabled.
     * - Expands the product view super attributes and selected attributes with discontinued postfix when all the discontinued
     *   variant attributes are selected and {@link \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig::isOnlyDiscontinuedVariantAttributesPostfixEnabled()} is enabled.
     * - Returns the same `ProductViewTransfer` if `ProductViewTransfer.attributeMap` is not provided or there is more
     *   than one attribute to be selected.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName)
    {
        return $this->getClient()
            ->expandDiscontinuedProductSuperAttributes($productViewTransfer, $localeName);
    }
}
