<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi;

use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeBridge;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartCodesRestApi\CartCodesRestApiConfig getConfig()
 */
class CartCodesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CART_CODE = 'FACADE_CART_CODE';
    public const FACADE_CARTS_REST_API = 'FACADE_CARTS_REST_API';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addCartCodeFacade($container);
        $container = $this->addCartRestApiFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartCodeFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART_CODE, function (Container $container) {
            return new CartCodesRestApiToCartCodeFacadeBridge($container->getLocator()->cartCode()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRestApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_CARTS_REST_API, function (Container $container) {
            return new CartCodesRestApiToCartsRestApiFacadeBridge($container->getLocator()->cartsRestApi()->facade());
        });

        return $container;
    }
}
