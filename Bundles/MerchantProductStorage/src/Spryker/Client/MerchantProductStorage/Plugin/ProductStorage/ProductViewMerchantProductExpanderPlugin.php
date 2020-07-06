<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductStorage\MerchantProductStorageFactory getFactory()
 */
class ProductViewMerchantProductExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    protected const SELECTED_ATTRIBUTE_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @phpstan-param array<mixed> $productData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName
    ): ProductViewTransfer {
        $productSelectedAttributes = $productViewTransfer->getSelectedAttributes();

        if (!isset($productSelectedAttributes[static::SELECTED_ATTRIBUTE_MERCHANT_REFERENCE])) {
            return $productViewTransfer;
        }

        $merchantProductStorageTransfer = $this->getFactory()
            ->createMerchantProductStorageReader()
            ->findOne($productViewTransfer->getIdProductAbstract());

        if (!$merchantProductStorageTransfer) {
            return $productViewTransfer;
        }

        if ($merchantProductStorageTransfer->getMerchantReference() !== $productSelectedAttributes[static::SELECTED_ATTRIBUTE_MERCHANT_REFERENCE]) {
            return $productViewTransfer;
        }

        return $productViewTransfer->setMerchantReference($merchantProductStorageTransfer->getMerchantReference());
    }
}
