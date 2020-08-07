<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Communication\Plugin\Publisher\ProductConfigurationStorage;

use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationStorage\Communication\ProductConfigurationStorageCommunicationFactory getFactory()
 */
class ProductConfigurationDeletePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->getFacade()->deleteProductConfigurationStorageCollection($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_UNPUBLISH,
            ProductConfigurationStorageConfig::ENTITY_SPY_PRODUCT_CONFIGURATION_DELETE,
        ];
    }
}
