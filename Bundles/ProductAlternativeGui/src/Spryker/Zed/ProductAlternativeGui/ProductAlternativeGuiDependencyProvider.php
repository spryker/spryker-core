<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeBridge;

class ProductAlternativeGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_ALTERNATIVE = 'FACADE_PRODUCT_ALTERNATIVE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductAlternativeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAlternativeFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_ALTERNATIVE] = function (Container $container) {
            return new ProductAlternativeGuiToProductAlternativeFacadeBridge(
                $container->getLocator()->productAlternative()->facade()
            );
        };

        return $container;
    }
}
