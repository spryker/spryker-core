<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToUserFacadeBridge;
use Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceBridge;

/**
 * @method \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig getConfig()
 */
class PriceProductScheduleDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_PROPEL = 'FACADE_PROPEL';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_USER = 'FACADE_USER';

    public const SERVICE_UTIL_CSV = 'SERVICE_UTIL_CSV';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPriceProductFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addUtilCsvService($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addPropelFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new PriceProductScheduleToUserFacadeBridge(
                $container->getLocator()->user()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new PriceProductScheduleToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
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
            return new PriceProductScheduleToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PROPEL, function (Container $container) {
            return new PriceProductScheduleToPropelFacadeBridge(
                $container->getLocator()->propel()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new PriceProductScheduleToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new PriceProductScheduleToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
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
            return new PriceProductScheduleToUtilCsvServiceBridge(
                $container->getLocator()->utilCsv()->service()
            );
        });

        return $container;
    }
}
