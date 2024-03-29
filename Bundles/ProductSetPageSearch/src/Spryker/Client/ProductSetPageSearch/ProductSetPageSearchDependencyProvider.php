<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductSetPageSearch\Dependency\Client\ProductSetPageSearchToProductSetStorageClientBridge;

class ProductSetPageSearchDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_SET_STORAGE = 'CLIENT_PRODUCT_SET_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS = 'PLUGIN_PRODUCT_SET_SEARCH_RESULT_FORMATTERS';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS = 'PLUGIN_PRODUCT_SET_SEARCH_QUERY_EXPANDERS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->provideProductSetStorageClient($container);
        $container = $this->provideSearchClient($container);
        $container = $this->provideProductSetListResultFormatterPlugins($container);
        $container = $this->provideProductSetListQueryExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideSearchClient(Container $container)
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return $container->getLocator()->search()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideProductSetListResultFormatterPlugins(Container $container)
    {
        $container->set(static::PLUGIN_PRODUCT_SET_LIST_RESULT_FORMATTERS, function (Container $container) {
            return $this->getProductSetListResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideProductSetListQueryExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGIN_PRODUCT_SET_LIST_QUERY_EXPANDERS, function (Container $container) {
            return $this->getProductSetListQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideProductSetStorageClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_SET_STORAGE, function (Container $container) {
            return new ProductSetPageSearchToProductSetStorageClientBridge($container->getLocator()->productSetStorage()->client());
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin>
     */
    protected function getProductSetListResultFormatterPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\Kernel\AbstractPlugin>
     */
    protected function getProductSetListQueryExpanderPlugins()
    {
        return [];
    }
}
