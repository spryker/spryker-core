<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode;

use Spryker\Shared\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeBridge;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartCodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    public const PLUGIN_CART_CODE_COLLECTION = 'PLUGIN_CART_CODE_COLLECTION';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addFacadeCalculation($container);
        $container = $this->addQuoteFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addFacadeCalculation(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new CartCodeToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE, function (Container $container) {
            return new CartCodeToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCartCodePluginCollection(Container $container): Container
    {
        $container[static::PLUGIN_CART_CODE_COLLECTION] = function () {
            return $this->getCartCodePluginCollection();
        };

        return $container;
    }

    /**
     * @return CartCodePluginInterface[]
     */
    protected function getCartCodePluginCollection(): array
    {
        return [];
    }
}
