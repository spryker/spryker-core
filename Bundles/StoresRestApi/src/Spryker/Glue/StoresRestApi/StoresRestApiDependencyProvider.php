<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCountryClientBridge;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientBridge;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToStoreClientBridge;

/**
 * @method \Spryker\Glue\StoresRestApi\StoresRestApiConfig getConfig()
 */
class StoresRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COUNTRY = 'CLIENT_COUNTRY';
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCountryClient($container);
        $container = $this->addCurrencyClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCountryClient(Container $container): Container
    {
        $container[static::CLIENT_COUNTRY] = function (Container $container) {
            return new StoresRestApiToCountryClientBridge($container->getLocator()->country()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container[static::CLIENT_CURRENCY] = function (Container $container) {
            return new StoresRestApiToCurrencyClientBridge($container->getLocator()->currency()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container[static::CLIENT_STORE] = function (Container $container) {
            return new StoresRestApiToStoreClientBridge($container->getLocator()->store()->client());
        };

        return $container;
    }
}
