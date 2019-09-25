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
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 */
class ProductDiscontinuedProductAvailabilityExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
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
        return $this->getClient()
            ->expandDiscontinuedProductAvailability($productViewTransfer, $localeName);
    }
}
