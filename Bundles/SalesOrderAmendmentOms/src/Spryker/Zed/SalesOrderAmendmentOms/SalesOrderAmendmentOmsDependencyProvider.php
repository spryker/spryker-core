<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeBridge;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeBridge;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeBridge;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceBridge;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class SalesOrderAmendmentOmsDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_SALES_ORDER_AMENDMENT = 'FACADE_SALES_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const SERVICE_SALES_ORDER_AMENDMENT = 'SERVICE_SALES_ORDER_AMENDMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addSalesOrderAmendmentService($container);
        $container = $this->addSalesOrderAmendmentFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSalesOrderAmendmentFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new SalesOrderAmendmentOmsToOmsFacadeBridge(
                $container->getLocator()->oms()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesOrderAmendmentOmsToSalesFacadeBridge(
                $container->getLocator()->sales()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderAmendmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_ORDER_AMENDMENT, function (Container $container) {
            return new SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeBridge(
                $container->getLocator()->salesOrderAmendment()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderAmendmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SALES_ORDER_AMENDMENT, function (Container $container) {
            return new SalesOrderAmendmentOmsToSalesOrderAmendmentServiceBridge(
                $container->getLocator()->salesOrderAmendment()->service(),
            );
        });

        return $container;
    }
}
