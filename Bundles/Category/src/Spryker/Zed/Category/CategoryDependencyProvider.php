<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category;

use Spryker\Zed\Category\Dependency\Facade\CategoryToEventBridge;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleBridge;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchBridge;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlBridge;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryDependencyProvider extends AbstractBundleDependencyProvider
{

    const CATEGORY_QUERY_CONTAINER = 'category query container';

    const FACADE_TOUCH = 'touch facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_URL = 'url facade';
    const FACADE_EVENT = 'facade event';

    const PLUGIN_GRAPH = 'graph plugin';
    const PLUGIN_STACK_RELATION_DELETE = 'delete relation plugin stack';
    const PLUGIN_STACK_RELATION_READ = 'read relation plugin stack';
    const PLUGIN_STACK_RELATION_UPDATE = 'update relation plugin stack';
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';
    const PLUGIN_CATEGORY_FORM_PLUGINS = 'PLUGIN_CATEGORY_FORM_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new CategoryToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new CategoryToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[static::FACADE_EVENT] = function (Container $container) {
            return new CategoryToEventBridge($container->getLocator()->event()->facade());
        };

        $container[self::PLUGIN_GRAPH] = function () {
            return $this->createGraphPlugin();
        };

        $container[static::PLUGIN_STACK_RELATION_DELETE] = Container::share(function () {
            return $this->getRelationDeletePluginStack();
        });

        $container[static::PLUGIN_STACK_RELATION_UPDATE] = Container::share(function () {
            return $this->getRelationUpdatePluginStack();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    protected function createGraphPlugin()
    {
        return new GraphPlugin();
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    protected function getRelationDeletePluginStack()
    {
        return [];
    }

    /**
     * @return array \Spryker\Zed\Category\Dependency\Plugin\CategoryUpdatePluginInterface[]
     */
    protected function getRelationUpdatePluginStack()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::PLUGIN_STACK_RELATION_READ] = Container::share(function () {
            return $this->getRelationReadPluginStack();
        });

        $container = $this->addCategoryFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFormPlugins(Container $container)
    {
        $container[static::PLUGIN_CATEGORY_FORM_PLUGINS] = function (Container $container) {
            return $this->getCategoryFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationReadPluginInterface[]
     */
    protected function getRelationReadPluginStack()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryFormPluginInterface[]
     */
    protected function getCategoryFormPlugins()
    {
        return [];
    }

}
