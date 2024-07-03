<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToMoneyFacadeBridge;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade\PriceProductMerchantCommissionConnectorToRuleEngineFacadeBridge;

/**
 * @method \Spryker\Zed\PriceProductMerchantCommissionConnector\PriceProductMerchantCommissionConnectorConfig getConfig()
 */
class PriceProductMerchantCommissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const FACADE_RULE_ENGINE = 'FACADE_RULE_ENGINE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addRuleEngineFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new PriceProductMerchantCommissionConnectorToMoneyFacadeBridge(
                $container->getLocator()->money()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRuleEngineFacade(Container $container): Container
    {
        $container->set(static::FACADE_RULE_ENGINE, function (Container $container) {
            return new PriceProductMerchantCommissionConnectorToRuleEngineFacadeBridge(
                $container->getLocator()->ruleEngine()->facade(),
            );
        });

        return $container;
    }
}
