<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeBridge;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeBridge;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeBridge;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeBridge;

class MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD = 'FACADE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD';
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';
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

        $container = $this->addMerchantRelationshipSalesOrderThresholdFacade($container);
        $container = $this->addMerchantRelationshipFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipSalesOrderThresholdFacade(Container $container)
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD] = function (Container $container) {
            return new MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeBridge(
                $container->getLocator()->merchantRelationshipSalesOrderThreshold()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container)
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP] = function (Container $container) {
            return new MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeBridge(
                $container->getLocator()->merchantRelationship()->facade()
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
            return new MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeBridge(
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
            return new MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
            );
        };

        return $container;
    }
}
