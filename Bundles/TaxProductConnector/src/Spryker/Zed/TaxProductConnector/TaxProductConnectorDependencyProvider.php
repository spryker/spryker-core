<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;

/**
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 */
class TaxProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'facade product';

    /**
     * @var string
     */
    public const FACADE_TAX = 'facade tax';

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @var string
     */
    public const PLUGINS_SHIPPING_ADDRESS_VALIDATOR = 'PLUGINS_SHIPPING_ADDRESS_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addTaxFacade($container);
        $container = $this->addShippingAddressValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new TaxProductConnectorToProductBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container)
    {
        $container->set(static::FACADE_TAX, function (Container $container) {
            return new TaxProductConnectorToTaxBridge($container->getLocator()->tax()->facade());
        });

        return $container;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShippingAddressValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHIPPING_ADDRESS_VALIDATOR, function () {
            return $this->getShippingAddressValidatorPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @return array<\Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin\ShippingAddressValidatorPluginInterface>
     */
    protected function getShippingAddressValidatorPlugins(): array
    {
        return [];
    }
}
