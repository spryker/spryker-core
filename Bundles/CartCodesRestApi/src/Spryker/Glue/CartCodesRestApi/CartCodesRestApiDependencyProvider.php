<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi;

use Spryker\Glue\CartCodesRestApi\Dependency\RestApiResource\CartCodesRestApiToCartsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig getConfig()
 */
class CartCodesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CARTS_REST_API_RESOURCE = 'CARTS_REST_API_RESOURCE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCartsResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartsResource(Container $container): Container
    {
        $container->set(static::CARTS_REST_API_RESOURCE, function (Container $container) {
            return new CartCodesRestApiToCartsRestApiResourceBridge(
                $container->getLocator()->cartsRestApi()->resource()
            );
        });

        return $container;
    }
}
