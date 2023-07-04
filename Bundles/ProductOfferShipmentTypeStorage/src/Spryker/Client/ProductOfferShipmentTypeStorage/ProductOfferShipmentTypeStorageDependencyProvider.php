<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToShipmentTypeStorageClientBridge;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStorageClientBridge;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientBridge;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Service\ProductOfferShipmentTypeStorageToSynchronizationServiceBridge;

class ProductOfferShipmentTypeStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT_TYPE_STORAGE = 'CLIENT_SHIPMENT_TYPE_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addStoreClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addShipmentTypeStorageClient($container);

        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToStorageClientBridge(
                $container->getLocator()->storage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToShipmentTypeStorageClientBridge(
                $container->getLocator()->shipmentTypeStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductOfferShipmentTypeStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service(),
            );
        });

        return $container;
    }
}
