<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Plugin\AvailabilityStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface getClient()
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory getFactory()
 */
class ProductConfigurationAvailabilityStorageStrategyPlugin extends AbstractPlugin implements AvailabilityStorageStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if product configuration exists or false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getClient()->isProductHasProductConfigurationInstance($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns true if product configuration is available or false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getClient()->isProductConcreteAvailable($productViewTransfer);
    }
}
