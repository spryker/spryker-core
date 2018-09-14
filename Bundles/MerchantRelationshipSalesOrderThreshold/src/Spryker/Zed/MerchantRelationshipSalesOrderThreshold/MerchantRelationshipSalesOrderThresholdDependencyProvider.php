<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeBridge;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeBridge;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeBridge;

class MerchantRelationshipSalesOrderThresholdDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES_ORDER_THRESHOLD = 'FACADE_SALES_ORDER_THRESHOLD';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addSalesOrderThresholdFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addStoreFacade($container);

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
            return new MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeBridge(
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
    protected function addGlossaryFacade(Container $container): Container
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new MerchantRelationshipSalesOrderThresholdToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
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
            return new MerchantRelationshipSalesOrderThresholdToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }
}
