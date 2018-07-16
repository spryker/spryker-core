<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeBridge;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeBridge;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeBridge;

class MinimumOrderValueDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_MINIMUM_ORDER_VALUE = 'FACADE_MINIMUM_ORDER_VALUE';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMinimumOrderValueFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMinimumOrderValueFacade(Container $container)
    {
        $container[static::FACADE_MINIMUM_ORDER_VALUE] = function (Container $container) {
            return new MinimumOrderValueDataImportToMinimumOrderValueFacadeBridge(
                $container->getLocator()->minimumOrderValue()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new MinimumOrderValueDataImportToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new MinimumOrderValueDataImportToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
            );
        };

        return $container;
    }
}
