<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeBridge;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeBridge;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeBridge;

/**
 * @method \Spryker\Zed\OrderAmendmentExample\OrderAmendmentExampleConfig getConfig()
 */
class OrderAmendmentExampleDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CHECKOUT = 'FACADE_CHECKOUT';

    /**
     * @var string
     */
    public const FACADE_SALES_ORDER_AMENDMENT = 'FACADE_SALES_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCheckoutFacade($container);
        $container = $this->addSalesOrderAmendmentFacade($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutFacade(Container $container): Container
    {
        $container->set(static::FACADE_CHECKOUT, function (Container $container) {
            return new OrderAmendmentExampleToCheckoutFacadeBridge(
                $container->getLocator()->checkout()->facade(),
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
            return new OrderAmendmentExampleToSalesOrderAmendmentFacadeBridge(
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
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new OrderAmendmentExampleToSalesFacadeBridge(
                $container->getLocator()->sales()->facade(),
            );
        });

        return $container;
    }
}
