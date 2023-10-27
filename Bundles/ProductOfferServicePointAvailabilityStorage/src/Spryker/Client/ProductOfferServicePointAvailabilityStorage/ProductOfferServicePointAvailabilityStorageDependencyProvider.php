<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientBridge;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientBridge;

class ProductOfferServicePointAvailabilityStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_STORAGE = 'CLIENT_PRODUCT_OFFER_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE = 'CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_FILTER = 'PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_FILTER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addProductOfferStorageClient($container);
        $container = $this->addProductOfferAvailabilityStorageClient($container);
        $container = $this->addProductOfferServicePointAvailabilityFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE, function (Container $container) {
            return new ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientBridge(
                $container->getLocator()->productOfferAvailabilityStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return new ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientBridge(
                $container->getLocator()->productOfferStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_FILTER, function () {
            return $this->getProductOfferServicePointAvailabilityFilterPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface>
     */
    protected function getProductOfferServicePointAvailabilityFilterPlugins(): array
    {
        return [];
    }
}
