<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToSearchClientBridge;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientBridge;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientBridge;
use Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\MerchantSearchQueryPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class MerchantSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    public const PLUGIN_MERCHANT_SEARCH_QUERY = 'PLUGIN_MERCHANT_SEARCH_QUERY';
    public const PLUGINS_MERCHANT_SEARCH_RESULT_FORMATTER = 'PLUGINS_MERCHANT_SEARCH_RESULT_FORMATTER';
    public const PLUGINS_MERCHANT_SEARCH_QUERY_EXPANDER = 'PLUGINS_MERCHANT_SEARCH_QUERY_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addZedRequestClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addSearchClient($container);
        $container = $this->addMerchantSearchQueryExpanderPlugins($container);
        $container = $this->addMerchantSearchQueryPlugin($container);
        $container = $this->addMerchantSearchResultFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new MerchantSearchToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

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
            return new MerchantSearchToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new MerchantSearchToSearchClientBridge(
                $container->getLocator()->search()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMerchantSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MERCHANT_SEARCH_QUERY, function () {
            return $this->createMerchantSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createMerchantSearchQueryPlugin(): QueryInterface
    {
        return new MerchantSearchQueryPlugin();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMerchantSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_SEARCH_QUERY_EXPANDER, function () {
            return $this->getMerchantSearchQueryExpanderPlugins();
        });

        return $container;
    }

   /**
    * @param \Spryker\Client\Kernel\Container $container
    *
    * @return \Spryker\Client\Kernel\Container
    */
    protected function addMerchantSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_SEARCH_RESULT_FORMATTER, function () {
            return $this->getMerchantSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function getMerchantSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function getMerchantSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
