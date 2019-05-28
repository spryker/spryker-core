<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductScheduleFacadeBridge;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceBridge;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductScheduleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_PRODUCT_SCHEDULE = 'FACADE_PRICE_PRODUCT_SCHEDULE';

    public const SERVICE_UTIL_CSV = 'SERVICE_UTIL_CSV';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addPriceProductScheduleFacade($container);

        $container = $this->addUtilCsvService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductScheduleFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT_SCHEDULE, function (Container $container) {
            return new PriceProductScheduleGuiToPriceProductScheduleFacadeBridge(
                $container->getLocator()->priceProductSchedule()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilCsvService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_CSV, function (Container $container) {
            return new PriceProductScheduleGuiToUtilCsvServiceBridge(
                $container->getLocator()->utilCsv()->service()
            );
        });

        return $container;
    }
}
