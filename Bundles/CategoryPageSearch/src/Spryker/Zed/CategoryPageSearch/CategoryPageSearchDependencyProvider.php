<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch;

use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeBridge;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeBridge;
use Spryker\Zed\CategoryPageSearch\Dependency\QueryContainer\CategoryPageSearchToCategoryQueryContainerBridge;
use Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 */
class CategoryPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';

    public const FACADE_CATEGORY = 'FACADE_CATEGORY';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_STORE = 'FACADE_STORE';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCategoryFacade($container);
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addCategoryQueryContainer($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new CategoryPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
            return new CategoryPageSearchToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CategoryPageSearchToCategoryFacadeBridge($container->getLocator()->category()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CategoryPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return new CategoryPageSearchToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        });

        return $container;
    }
}
