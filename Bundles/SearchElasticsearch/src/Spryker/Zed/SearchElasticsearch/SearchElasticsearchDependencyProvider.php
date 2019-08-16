<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingBridge;

/**
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH = 'search client';
    public const SERVICE_UTIL_ENCODING = 'util encoding service';
    public const PLUGIN_SEARCH_PAGE_MAPS = 'PLUGIN_SEARCH_PAGE_MAPS';
    public const SEARCH_INSTALLER_PLUGINS = 'SEARCH_INSTALLER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchClient($container);
        $container = $this->addUtilEncodingFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->searchElasticsearch()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingFacade(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new SearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
