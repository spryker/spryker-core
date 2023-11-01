<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeBridge;

/**
 * @method \Spryker\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiConfig getConfig()
 */
class ServicePointCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT_CART = 'FACADE_SERVICE_POINT_CART';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addServicePointCartFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServicePointCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT_CART, function (Container $container) {
            return new ServicePointCartsRestApiToServicePointCartFacadeBridge(
                $container->getLocator()->servicePointCart()->facade(),
            );
        });

        return $container;
    }
}
