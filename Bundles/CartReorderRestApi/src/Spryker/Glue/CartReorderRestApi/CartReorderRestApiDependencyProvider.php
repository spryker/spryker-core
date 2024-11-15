<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi;

use Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToCartReorderClientBridge;
use Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\CartReorderRestApi\Dependency\Glue\CartReorderRestApiToCartsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig getConfig()
 */
class CartReorderRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CART_REORDER = 'CLIENT_CART_REORDER';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const RESOURCE_CARTS_REST_API = 'RESOURCE_CARTS_REST_API';

    /**
     * @var string
     */
    public const PLUGINS_REST_CART_REORDER_ATTRIBUTES_MAPPER = 'PLUGINS_REST_CART_REORDER_ATTRIBUTES_MAPPER';

    /**
     * @var string
     */
    public const PLUGINS_REST_CART_REORDER_ATTRIBUTES_VALIDATOR = 'PLUGINS_REST_CART_REORDER_ATTRIBUTES_VALIDATOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCartReorderClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addCartsRestApiResource($container);
        $container = $this->addRestCartReorderAttributesMapperPlugins($container);
        $container = $this->addRestCartReorderAttributesValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartReorderClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART_REORDER, function (Container $container) {
            return new CartReorderRestApiToCartReorderClientBridge(
                $container->getLocator()->cartReorder()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new CartReorderRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

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
            return new CartReorderRestApiToCartsRestApiResourceBridge(
                $container->getLocator()->cartsRestApi()->resource(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestCartReorderAttributesMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_CART_REORDER_ATTRIBUTES_MAPPER, function () {
            return $this->getRestCartReorderAttributesMapperPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\RestCartReorderAttributesMapperPluginInterface>
     */
    protected function getRestCartReorderAttributesMapperPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestCartReorderAttributesValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_CART_REORDER_ATTRIBUTES_VALIDATOR, function () {
            return $this->getRestCartReorderAttributesValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\RestCartReorderAttributesValidatorPluginInterface>
     */
    protected function getRestCartReorderAttributesValidatorPlugins(): array
    {
        return [];
    }
}
