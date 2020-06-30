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
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<mixed> $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName)
    {
        if (isset($productViewTransfer->getSelectedAttributes()['merchant_reference'])) {
            $merchantProductStorageTransfer = $this->getFactory()
                ->createMerchantProductStorageReader()
                ->findOne($productViewTransfer->getIdProductAbstract());

            if (
                $merchantProductStorageTransfer &&
                $merchantProductStorageTransfer->getMerchantReference() === $productViewTransfer->getSelectedAttributes()['merchant_reference']
            ) {
                $productViewTransfer->setMerchantReference($merchantProductStorageTransfer->getMerchantReference());
            }
        }

        return $productViewTransfer;
    }
}
