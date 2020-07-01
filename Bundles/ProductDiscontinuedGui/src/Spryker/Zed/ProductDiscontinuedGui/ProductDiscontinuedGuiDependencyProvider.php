<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeBridge;

class ProductDiscontinuedGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_DISCONTINUED = 'FACADE_PRODUCT_DISCONTINUED';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductDiscontinuedFacade($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductDiscontinuedFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_DISCONTINUED, function (Container $container) {
            return new ProductDiscontinuedGuiToProductDiscontinuedFacadeBridge($container->getLocator()->productDiscontinued()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductDiscontinuedGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }
}
