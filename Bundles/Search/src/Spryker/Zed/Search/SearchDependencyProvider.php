<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use GuzzleHttp\Client;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingBridge;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH = 'search client';
    public const SERVICE_UTIL_ENCODING = 'util encoding service';
    public const PLUGIN_SEARCH_PAGE_MAPS = 'PLUGIN_SEARCH_PAGE_MAPS';
    public const SEARCH_SOURCE_INSTALLER_PLUGINS = 'SEARCH_SCHEMA_INSTALLER_PLUGINS';
    public const SEARCH_MAP_INSTALLER_PLUGINS = 'SEARCH_MAP_INSTALLER_PLUGINS';
    public const GUZZLE_CLIENT = 'GUZZLE_CLIENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addUtilEncodingFacade($container);
        $container = $this->addPluginSearchPageMaps($container);
        $container = $this->addSearchSourceInstallerPlugins($container);
        $container = $this->addSearchMapInstallerPlugins($container);
        $container = $this->addGuzzleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingFacade(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new SearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPluginSearchPageMaps(Container $container)
    {
        $container[static::PLUGIN_SEARCH_PAGE_MAPS] = function (Container $container) {
            return $this->getSearchPageMapPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface[]
     */
    protected function getSearchPageMapPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchSourceInstallerPlugins(Container $container): Container
    {
        $container->set(static::SEARCH_SOURCE_INSTALLER_PLUGINS, function () {
            return $this->getSearchSourceInstallerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface[]
     */
    protected function getSearchSourceInstallerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchMapInstallerPlugins(Container $container): Container
    {
        $container->set(static::SEARCH_MAP_INSTALLER_PLUGINS, function () {
            return $this->getSearchMapInstallerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface[]
     */
    protected function getSearchMapInstallerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuzzleClient(Container $container)
    {
        $container[static::GUZZLE_CLIENT] = function (Container $container) {
            return new Client();
        };

        return $container;
    }
}
