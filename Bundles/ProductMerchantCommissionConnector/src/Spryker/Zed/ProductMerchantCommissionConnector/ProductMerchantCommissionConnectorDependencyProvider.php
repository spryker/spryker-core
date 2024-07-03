<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeBridge;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeBridge;

/**
 * @method \Spryker\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorConfig getConfig()
 */
class ProductMerchantCommissionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_RULE_ENGINE = 'FACADE_RULE_ENGINE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addRuleEngineFacade($container);
        $container = $this->addProductFacade($container);

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
            return new ProductMerchantCommissionConnectorToRuleEngineFacadeBridge(
                $container->getLocator()->ruleEngine()->facade(),
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
            return new ProductMerchantCommissionConnectorToProductFacadeBridge(
                $container->getLocator()->product()->facade(),
            );
        });

        return $container;
    }
}
