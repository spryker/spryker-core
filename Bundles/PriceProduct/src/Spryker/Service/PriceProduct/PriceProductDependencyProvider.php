<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductConfig getConfig()
 */
class PriceProductDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_PRICE_PRODUCT_DECISION = 'PLUGIN_PRICE_PRODUCT_DECISION';

    /**
     * @var string
     */
    public const PLUGIN_PRE_BUILD_PRICE_PRODUCT_GROUP_KEY = 'PLUGIN_PRE_BUILD_PRICE_PRODUCT_GROUP_KEY';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addPriceProductDecisionPlugins($container);
        $container = $this->addPreBuildPriceProductGroupKeyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addPriceProductDecisionPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRICE_PRODUCT_DECISION, function () {
            return $this->getPriceProductDecisionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addPreBuildPriceProductGroupKeyPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRE_BUILD_PRICE_PRODUCT_GROUP_KEY, function (Container $container) {
            return $this->getPreBuildPriceProductGroupKeyPlugins();
        });

        return $container;
    }

    /**
     * @return array<int, \Spryker\Service\PriceProductExtension\Dependency\Plugin\PreBuildPriceProductGroupKeyPluginInterface>
     */
    protected function getPreBuildPriceProductGroupKeyPlugins(): array
    {
        return [];
    }

    /**
     * The plugins in this stack will filter data returned by price query.
     *
     * @return array<\Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface>
     */
    protected function getPriceProductDecisionPlugins(): array
    {
        return [];
    }
}
