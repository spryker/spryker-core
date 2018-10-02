<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductSet\Dependency\Client\ProductSetToLocaleBridge;
use Spryker\Client\ProductSet\Dependency\Client\ProductSetToStorageBridge;

class ProductSetDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    public const PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS = 'PLUGIN_PRODUCT_SET_SEARCH_RESULT_FORMATTERS';
    public const PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS = 'PLUGIN_PRODUCT_SET_SEARCH_QUERY_EXPANDERS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $this->provideSearchClient($container);
        $this->provideStorageClient($container);
        $this->provideLocaleClient($container);

        $this->provideProductSetListResultFormatterPlugins($container);
        $this->provideProductSetListQueryExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideSearchClient(Container $container)
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetListResultFormatterPlugins(Container $container)
    {
        $container[static::PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS] = function (Container $container) {
            return $this->getProductSetListResultFormatterPlugins();
        };
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetListQueryExpanderPlugins(Container $container)
    {
        $container[static::PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS] = function (Container $container) {
            return $this->getProductSetListQueryExpanderPlugins();
        };
    }

    /**
     * @return array
     */
    protected function getProductSetListResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getProductSetListQueryExpanderPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideStorageClient(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductSetToStorageBridge($container->getLocator()->storage()->client());
        };
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductSetToLocaleBridge($container->getLocator()->locale()->client());
        };
    }
}
