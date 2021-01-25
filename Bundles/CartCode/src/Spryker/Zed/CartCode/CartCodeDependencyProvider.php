<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode;

use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeBridge;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartCode\CartCodeConfig getConfig()
 */
class CartCodeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    public const PLUGINS_CART_CODE = 'PLUGINS_CART_CODE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addFacadeCalculation($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addCartCodePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeCalculation(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new CartCodeToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE, function (Container $container) {
            return new CartCodeToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartCodePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_CODE, function () {
            return $this->getCartCodePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected function getCartCodePlugins(): array
    {
        return [];
    }
}
