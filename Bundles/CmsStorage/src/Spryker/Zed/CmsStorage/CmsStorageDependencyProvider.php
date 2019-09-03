<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage;

use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsBridge;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToStoreFacadeBridge;
use Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToCmsQueryContainerBridge;
use Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToLocaleQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 */
class CmsStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_CMS_PAGE = 'QUERY_CONTAINER_CMS_PAGE';
    public const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PLUGIN_CONTENT_WIDGET_DATA_EXPANDER = 'PLUGIN_CONTENT_WIDGET_DATA_EXPANDER';
    public const FACADE_CMS = 'FACADE_CMS';
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new CmsStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsStorageToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[static::PLUGIN_CONTENT_WIDGET_DATA_EXPANDER] = function (Container $container) {
            return $this->getContentWidgetDataExpander();
        };

        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_CMS_PAGE] = function (Container $container) {
            return new CmsStorageToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return new CmsStorageToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new CmsStorageToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected function getContentWidgetDataExpander()
    {
        return [];
    }
}
