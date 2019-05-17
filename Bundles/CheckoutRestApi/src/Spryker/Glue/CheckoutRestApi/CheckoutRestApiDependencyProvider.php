<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig getConfig()
 */
class CheckoutRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const PLUGINS_CHECKOUT_REQUEST_ATTRIBUTES_VALIDATOR = 'PLUGINS_CHECKOUT_REQUEST_ATTRIBUTES_VALIDATOR';
    public const PLUGINS_CHECKOUT_RESPONSE_MAPPER = 'PLUGINS_CHECKOUT_RESPONSE_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addCheckoutRequestAttributesValidatorPlugins($container);
        $container = $this->addCheckoutResponseMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return new CheckoutRestApiToGlossaryStorageClientBridge($container->getLocator()->glossaryStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCheckoutRequestAttributesValidatorPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CHECKOUT_REQUEST_ATTRIBUTES_VALIDATOR] = function () {
            return $this->getCheckoutRequestAttributesValidatorPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface[]
     */
    protected function getCheckoutRequestAttributesValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCheckoutResponseMapperPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CHECKOUT_RESPONSE_MAPPER] = function () {
            return $this->getCheckoutResponseMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutResponseMapperPluginInterface[]
     */
    protected function getCheckoutResponseMapperPlugins(): array
    {
        return [];
    }
}
