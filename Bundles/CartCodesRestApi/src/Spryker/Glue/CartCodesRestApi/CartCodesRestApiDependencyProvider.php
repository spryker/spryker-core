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
    public const RESOURCE_CARTS_REST_API = 'RESOURCE_CARTS_REST_API';
    public const PLUGINS_DISCOUNT_MAPPER = 'PLUGINS_DISCOUNT_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCartsRestApiResource($container);
        $container = $this->addDiscountMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_CARTS_REST_API, function (Container $container) {
            return new CartCodesRestApiToCartsRestApiResourceBridge(
                $container->getLocator()->cartsRestApi()->resource()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addDiscountMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DISCOUNT_MAPPER, function () {
            return $this->getDiscountMapperPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\CartCodesRestApiExtension\Dependency\Plugin\DiscountMapperPluginInterface[]
     */
    protected function getDiscountMapperPlugins(): array
    {
        return [];
    }
}
