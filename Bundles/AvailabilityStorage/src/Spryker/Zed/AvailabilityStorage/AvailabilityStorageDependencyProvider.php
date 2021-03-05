<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\AvailabilityStorage\Dependency\Facade\AvailabilityStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer\AvailabilityStorageToAvailabilityQueryContainerBridge;
use Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer\AvailabilityStorageToProductQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 */
class AvailabilityStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_AVAILABILITY = 'QUERY_CONTAINER_AVAILABILITY';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new AvailabilityStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container->set(static::STORE, function (Container $container) {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_AVAILABILITY, function (Container $container) {
            return new AvailabilityStorageToAvailabilityQueryContainerBridge($container->getLocator()->availability()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new AvailabilityStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        $container = $this->addProductAbstractPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ABSTRACT, $container->factory(function (): SpyProductAbstractQuery {
            return SpyProductAbstractQuery::create();
        }));

        return $container;
    }
}
