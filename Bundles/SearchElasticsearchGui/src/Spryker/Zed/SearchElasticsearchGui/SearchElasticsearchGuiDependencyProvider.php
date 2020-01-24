<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Client\SearchElasticsearchGuiToSearchElasticsearchClientBridge;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeBridge;

class SearchElasticsearchGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH_ELASTICSEARCH = 'CLIENT_SEARCH_ELASTICSEARCH';
    public const FACADE_SEARCH_ELASTICSEARCH = 'FACADE_SEARCH_ELASTICSEARCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchElasticsearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchElasticsearchClient($container);
        $container = $this->addSearchElasticsearchFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchElasticsearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH_ELASTICSEARCH, function (Container $container) {
            return new SearchElasticsearchGuiToSearchElasticsearchClientBridge(
                $container->getLocator()->searchElasticsearch()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchElasticsearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_SEARCH_ELASTICSEARCH, function (Container $container) {
            return new SearchElasticsearchGuiToSearchElasticsearchFacadeBridge(
                $container->getLocator()->searchElasticsearch()->facade()
            );
        });

        return $container;
    }
}
