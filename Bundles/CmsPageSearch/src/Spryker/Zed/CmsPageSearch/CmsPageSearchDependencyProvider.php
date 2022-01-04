<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch;

use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToLocaleFacadeBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToStoreFacadeBridge;
use Spryker\Zed\CmsPageSearch\Dependency\QueryContainer\CmsPageSearchToCmsQueryContainerBridge;
use Spryker\Zed\CmsPageSearch\Dependency\QueryContainer\CmsPageSearchToLocaleQueryContainerBridge;
use Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_CMS_PAGE = 'QUERY_CONTAINER_CMS_PAGE';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const FACADE_CMS = 'FACADE_CMS';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new CmsPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CmsPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        $container->set(static::FACADE_CMS, function (Container $container) {
            return new CmsPageSearchToCmsBridge($container->getLocator()->cms()->facade());
        });

        $container = $this->addLocaleFacade($container);
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
        $container->set(static::QUERY_CONTAINER_CMS_PAGE, function (Container $container) {
            return new CmsPageSearchToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_LOCALE, function (Container $container) {
            return new CmsPageSearchToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CmsPageSearchToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

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
            return new CmsPageSearchToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }
}
