<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage;

use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 */
class MerchantOpeningHoursStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const PROPEL_QUERY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE = 'PROPEL_QUERY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE';
    public const PROPEL_QUERY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE = 'PROPEL_QUERY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMerchantFacade($container);

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
        $container = $this->addMerchantOpeningHoursWeekdaySchedulePropelQuery($container);
        $container = $this->addMerchantOpeningHoursDateSchedulePropelQuery($container);

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
            return new MerchantOpeningHoursStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantOpeningHoursStorageToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOpeningHoursWeekdaySchedulePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE, $container->factory(function () {
            return SpyMerchantOpeningHoursWeekdayScheduleQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOpeningHoursDateSchedulePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE, $container->factory(function () {
            return SpyMerchantOpeningHoursDateScheduleQuery::create();
        }));

        return $container;
    }
}
