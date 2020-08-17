<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory getFactory()
 */
class ProductConfigurationStorageClient extends AbstractClient implements ProductConfigurationStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceReader()->findProductConfigurationInstanceBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void {
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfiguration(
        ProductViewTransfer $productViewTransfer
    ): productViewTransfer {
        return $this->getFactory()
            ->createProductViewExpander()->expandWithProductConfigurationInstance($productViewTransfer);
    }
}
