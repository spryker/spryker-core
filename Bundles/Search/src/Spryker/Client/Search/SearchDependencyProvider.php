<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface;
use Spryker\Client\Search\Exception\MissingSearchConfigPluginException;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\Search\SearchConfig getConfig()
 */
class SearchDependencyProvider extends AbstractDependencyProvider
{
    public const SEARCH_CONFIG_BUILDER = 'search config builder';
    public const CLIENT_ADAPTER_PLUGINS = 'CLIENT_ADAPTER_PLUGINS';
    public const SEARCH_CONFIG_EXPANDER_PLUGINS = 'search config expander plugins';
    public const STORE = 'store';
    public const PLUGIN_MONEY = 'money plugin';
    public const SEARCH_CONTEXT_MAPPER_PLUGINS = 'SEARCH_CONTEXT_MAPPER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->provideStore($container);
        $container = $this->addClientAdapterPlugins($container);

        $container->set(static::SEARCH_CONFIG_BUILDER, function (Container $container) {
            return $this->createSearchConfigBuilderPlugin($container);
        });

        $container->set(static::SEARCH_CONFIG_EXPANDER_PLUGINS, function (Container $container) {
            return $this->createSearchConfigExpanderPlugins($container);
        });

        $container = $this->addMoneyPlugin($container);
        $container = $this->addSearchContextMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container->set(static::PLUGIN_MONEY, function () {
            return new MoneyPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @throws \Spryker\Client\Search\Exception\MissingSearchConfigPluginException
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface
     */
    protected function createSearchConfigBuilderPlugin(Container $container)
    {
        throw new MissingSearchConfigPluginException(sprintf(
            'Missing instance of %s! You need to implement your own plugin and instantiate it in your own SearchDependencyProvider::createSearchConfigBuilder() to be able to search.',
            SearchConfigBuilderInterface::class
        ));
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected function createSearchConfigExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addClientAdapterPlugins(Container $container): Container
    {
        $container->set(static::CLIENT_ADAPTER_PLUGINS, function () {
            return $this->getClientAdapterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[]
     */
    protected function getClientAdapterPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchContextMapperPlugins(Container $container): Container
    {
        $container->set(static::SEARCH_CONTEXT_MAPPER_PLUGINS, function () {
            return $this->getSearchContextMapperPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SourceIdentifiertMapperPluginInterface[]
     */
    protected function getSearchContextMapperPlugins(): array
    {
        return [];
    }
}
