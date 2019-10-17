<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientBridge;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientBridge;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const PLUGINS_SEARCH_CONFIG_EXPANDER = 'PLUGINS_SEARCH_CONFIG_EXPANDER';
    public const PLUGIN_SEARCH_CONFIG_BUILDER = 'PLUGIN_SEARCH_SEARCH_CONFIG_BUILDER';

    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addSearchConfigBuilderPlugin($container);
        $container = $this->addSearchConfigExpanderPlugins($container);
        $container = $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigBuilderPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SEARCH_CONFIG_BUILDER, function (Container $container): SearchConfigBuilderPluginInterface {
            return $this->getSearchConfigBuilderPlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface|null
     */
    protected function getSearchConfigBuilderPlugin(Container $container): ?SearchConfigBuilderPluginInterface
    {
        return null;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container): SearchElasticsearchToStoreClientInterface {
            return new SearchElasticsearchToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container): SearchElasticsearchToLocaleClientInterface {
            return new SearchElasticsearchToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_CONFIG_EXPANDER, function (Container $container): array {
            return $this->getSearchConfigExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected function getSearchConfigExpanderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, function (): MoneyPluginInterface {
            return $this->getMoneyPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function getMoneyPlugin(): MoneyPluginInterface
    {
        return new MoneyPlugin();
    }
}
