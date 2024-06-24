<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeBridge;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeBridge;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeBridge;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 */
class SalesMerchantCommissionDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_COMMISSION = 'FACADE_MERCHANT_COMMISSION';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @var string
     */
    public const PLUGINS_POST_REFUND_MERCHANT_COMMISSION = 'PLUGINS_POST_REFUND_MERCHANT_COMMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantCommissionFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addPostRefundMerchantCommissionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCommissionFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_COMMISSION, function (Container $container) {
            return new SalesMerchantCommissionToMerchantCommissionFacadeBridge(
                $container->getLocator()->merchantCommission()->facade(),
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
            return new SalesMerchantCommissionToSalesFacadeBridge(
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
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new SalesMerchantCommissionToCalculationFacadeBridge(
                $container->getLocator()->calculation()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostRefundMerchantCommissionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_REFUND_MERCHANT_COMMISSION, function () {
            return $this->getPostRefundMerchantCommissionPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface>
     */
    protected function getPostRefundMerchantCommissionPlugins(): array
    {
        return [];
    }
}
