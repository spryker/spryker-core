<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientBridge;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientBridge;

/**
 * @method \Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiConfig getConfig()
 */
class ProductOfferAvailabilitiesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE = 'CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductOfferAvailabilityStorageClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductOfferAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE, function (Container $container) {
            return new ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientBridge(
                $container->getLocator()->productOfferAvailabilityStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductOfferAvailabilitiesRestApiToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }
}
