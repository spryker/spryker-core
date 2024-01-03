<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceBridge;

/**
 * @method \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    /**
     * @var string
     */
    public const PLUGINS_ADDRESS_SOURCE_CHECKER = 'PLUGINS_ADDRESS_SOURCE_CHECKER';

    /**
     * @var string
     */
    public const PLUGINS_SHIPPING_ADDRESS_VALIDATION_STRATEGY = 'PLUGINS_SHIPPING_ADDRESS_VALIDATION_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_REST_ADDRESS_RESPONSE_MAPPER = 'PLUGINS_REST_ADDRESS_RESPONSE_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addShipmentService($container);
        $container = $this->addAddressSourceCheckerPlugins($container);
        $container = $this->addShipmentAddressValidationStrategyPlugins($container);
        $container = $this->addRestAddressResponseMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new ShipmentsRestApiToShipmentServiceBridge($container->getLocator()->shipment()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShipmentAddressValidationStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHIPPING_ADDRESS_VALIDATION_STRATEGY, function () {
            return $this->getShippingAddressValidationStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAddressSourceCheckerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ADDRESS_SOURCE_CHECKER, function () {
            return $this->getAddressSourceCheckerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestAddressResponseMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_ADDRESS_RESPONSE_MAPPER, function () {
            return $this->getRestAddressResponseMapperPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface>
     */
    protected function getAddressSourceCheckerPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface>
     */
    protected function getShippingAddressValidationStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\RestAddressResponseMapperPluginInterface>
     */
    protected function getRestAddressResponseMapperPlugins(): array
    {
        return [];
    }
}
