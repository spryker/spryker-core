<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui;

use Orm\Zed\Content\Persistence\Base\SpyContentQuery;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridge;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridge;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ContentGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_CONTENT_ITEM_PLUGINS = 'PLUGIN_CONTENT_ITEM_PLUGINS';
    public const PROPEL_QUERY_CONTENT = 'PROPEL_QUERY_CONTENT';
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_CONTENT = 'FACADE_CONTENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addPropelContentQuery($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addLocaleFacadeService($container);
        $container = $this->addContentFacade($container);
        $container = $this->addContentPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentPlugins(Container $container)
    {
        $container[static::PLUGIN_CONTENT_ITEM_PLUGINS] = function () {
            return $this->getContentPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface[]
     */
    protected function getContentPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacadeService(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ContentGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelContentQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CONTENT] = function () {
            return SpyContentQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_DATE_TIME] = function (Container $container) {
            return new ContentGuiToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentFacade(Container $container): Container
    {
        $container[static::FACADE_CONTENT] = function (Container $container) {
            return new ContentGuiToContentFacadeBridge(
                $container->getLocator()->content()->facade()
            );
        };

        return $container;
    }
}
