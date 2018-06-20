<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\PriceProduct\Plugin\DefaultPriceDimensionDecisionPlugin;

class PriceProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_PRICE_PRODUCT_DECISION = 'PLUGIN_PRICE_PRODUCT_DECISION';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $this->addPriceProductDecisionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addPriceProductDecisionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRICE_PRODUCT_DECISION] = function (Container $container) {
            return $this->getPriceProductDecisionPlugins();
        };

        return $container;
    }

    /**
     * The plugins in this stack will filter data returned by price query.
     *
     * @return \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected function getPriceProductDecisionPlugins(): array
    {
        return [
            new DefaultPriceDimensionDecisionPlugin(),
        ];
    }
}
