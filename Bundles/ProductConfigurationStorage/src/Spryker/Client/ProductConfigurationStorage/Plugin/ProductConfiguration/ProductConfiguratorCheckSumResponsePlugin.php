<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Plugin\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface getClient()
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory getFactory()
 */
class ProductConfiguratorCheckSumResponsePlugin extends AbstractPlugin implements ProductConfiguratorResponsePluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates response trough validators stack.
     * - Saves product configuration instance to the session storage when source type is pdp.
     * - Adjusts cart item quantity according to product configuration quantity restrictions.
     * - Replaces quote item product configuration with new one when source type is cart page.
     * - Returns `isSuccessful=true` on success or `isSuccessful=false` with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->getClient()->processProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData
        );
    }
}
