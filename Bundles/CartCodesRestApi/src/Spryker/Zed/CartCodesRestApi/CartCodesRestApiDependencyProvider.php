<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartCodesRestApi\CartCodesRestApiConfig getConfig()
 */
class CartCodesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CART_CODE = 'FACADE_CART_CODE';
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCartCodeFacade($container);
        $container = $this->addQuoteFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCartCodeFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART_CODE, function (Container $container) {
            $container->getLocator()->cartCode()->facade();
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
            $container->getLocator()->quote()->facade();
        });

        return $container;
    }
}
