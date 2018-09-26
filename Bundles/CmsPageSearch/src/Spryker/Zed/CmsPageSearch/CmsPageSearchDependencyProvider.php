<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchBridge;
use Spryker\Zed\CmsPageSearch\Dependency\QueryContainer\CmsPageSearchToCmsQueryContainerBridge;
use Spryker\Zed\CmsPageSearch\Dependency\QueryContainer\CmsPageSearchToLocaleQueryContainerBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_CMS_PAGE = 'QUERY_CONTAINER_CMS_PAGE';
    public const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    public const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const FACADE_CMS = 'FACADE_CMS';
    public const FACADE_SEARCH = 'FACADE_SEARCH';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const STORE = 'store';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new CmsPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsPageSearchToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        $container[self::FACADE_SEARCH] = function (Container $container) {
            return new CmsPageSearchToSearchBridge($container->getLocator()->search()->facade());
        };

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
            return new CmsPageSearchToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return new CmsPageSearchToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        };

        return $container;
    }
}
