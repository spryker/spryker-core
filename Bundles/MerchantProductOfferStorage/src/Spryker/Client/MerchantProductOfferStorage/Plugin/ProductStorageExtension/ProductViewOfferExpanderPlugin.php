<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin\ProductStorageExtension;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class ProductViewOfferExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the transfer object with the product offer reference.
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName)
    {
        return $productViewTransfer->setProductOfferReference(
            $this->getFactory()->createProductConcreteDefaultProductOffer()->findProductOfferReference($productViewTransfer)
        );
    }
}
