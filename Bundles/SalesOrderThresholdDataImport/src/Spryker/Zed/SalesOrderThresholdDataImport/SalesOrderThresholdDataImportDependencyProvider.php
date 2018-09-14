<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeBridge;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeBridge;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeBridge;

class SalesOrderThresholdDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_SALES_ORDER_THRESHOLD = 'FACADE_SALES_ORDER_THRESHOLD';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addSalesOrderThresholdFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderThresholdFacade(Container $container): Container
    {
        $container[static::FACADE_SALES_ORDER_THRESHOLD] = function (Container $container) {
            return new SalesOrderThresholdDataImportToSalesOrderThresholdFacadeBridge(
                $container->getLocator()->salesOrderThreshold()->facade()
            );
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
        $container[static::FACADE_STORE] = function (Container $container) {
            return new SalesOrderThresholdDataImportToStoreFacadeBridge(
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
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new SalesOrderThresholdDataImportToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
            );
        };

        return $container;
    }
}
