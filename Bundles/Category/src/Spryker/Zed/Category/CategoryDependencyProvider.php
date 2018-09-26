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
    public const CATEGORY_QUERY_CONTAINER = 'category query container';

    public const FACADE_TOUCH = 'touch facade';
    public const FACADE_LOCALE = 'locale facade';
    public const FACADE_URL = 'url facade';
    public const FACADE_EVENT = 'facade event';

    public const PLUGIN_GRAPH = 'graph plugin';
    public const PLUGIN_STACK_RELATION_DELETE = 'delete relation plugin stack';
    public const PLUGIN_STACK_RELATION_READ = 'read relation plugin stack';
    public const PLUGIN_STACK_RELATION_UPDATE = 'update relation plugin stack';
    public const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';
    public const PLUGIN_CATEGORY_FORM_PLUGINS = 'PLUGIN_CATEGORY_FORM_PLUGINS';
    public const PLUGINS_CATEGORY_URL_PATH = 'PLUGINS_CATEGORY_URL_PATH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addTouchFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUrlFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addGraphPlugin($container);
        $container = $this->addRelationDeletePluginStack($container);
        $container = $this->addRelationUpdatePluginStack($container);
        $container = $this->addCategoryUrlPathPlugins($container);

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
        $container = $this->addLocaleFacade($container);
        $container = $this->addRelationReadPluginStack($container);
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new CategoryToTouchBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CategoryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlFacade(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return new CategoryToUrlBridge($container->getLocator()->url()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container)
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new CategoryToEventBridge($container->getLocator()->event()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGraphPlugin(Container $container)
    {
        $container[self::PLUGIN_GRAPH] = function () {
            return $this->createGraphPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRelationDeletePluginStack(Container $container)
    {
        $container[static::PLUGIN_STACK_RELATION_DELETE] = Container::share(function () {
            return $this->getRelationDeletePluginStack();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRelationUpdatePluginStack(Container $container)
    {
        $container[static::PLUGIN_STACK_RELATION_UPDATE] = Container::share(function () {
            return $this->getRelationUpdatePluginStack();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryUrlPathPlugins(Container $container)
    {
        $container[static::PLUGINS_CATEGORY_URL_PATH] = Container::share(function () {
            return $this->getCategoryUrlPathPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRelationReadPluginStack(Container $container)
    {
        $container[static::PLUGIN_STACK_RELATION_READ] = Container::share(function () {
            return $this->getRelationReadPluginStack();
        });

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

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected function getCategoryUrlPathPlugins()
    {
        return [];
    }
}
