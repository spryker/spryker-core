<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_SHARED_CART = 'FACADE_SHARED_CART';
    public const CLIENT_SHARED_CARTS_REST_API = 'CLIENT_SHARED_CARTS_REST_API';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addSharedCartFacade($container);
        $container = $this->addSharedCartsRestApiClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addQuoteFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return $container->getLocator()->quote()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSharedCartFacade(Container $container): Container
    {
        $container[static::FACADE_SHARED_CART] = function (Container $container) {
            return $container->getLocator()->sharedCart()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSharedCartsRestApiClient(Container $container): Container
    {
        $container[static::CLIENT_SHARED_CARTS_REST_API] = function (Container $container) {
            return $container->getLocator()->sharedCartsRestApi()->client();
        };

        return $container;
    }
}
