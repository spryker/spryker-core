<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountBridge;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleBridge;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductBridge;

/**
 * @method \Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorConfig getConfig()
 */
class ProductDiscountConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_DISCOUNT = 'FACADE_DISCOUNT';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ATTRIBUTE_COLLECTOR_EXPANDER = 'PLUGINS_PRODUCT_ATTRIBUTE_COLLECTOR_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ATTRIBUTE_DECISION_RULE_EXPANDER = 'PLUGINS_PRODUCT_ATTRIBUTE_DECISION_RULE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_DISCOUNT, function (Container $container) {
            return new ProductDiscountConnectorToDiscountBridge($container->getLocator()->discount()->facade());
        });

        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductDiscountConnectorToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductDiscountConnectorToProductBridge($container->getLocator()->product()->facade());
        });

        $container = $this->addProductAttributeCollectorExpanderPlugins($container);
        $container = $this->addProductAttributeDecisionRuleExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeCollectorExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ATTRIBUTE_COLLECTOR_EXPANDER, function () {
            return $this->getProductAttributeCollectorExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeDecisionRuleExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ATTRIBUTE_DECISION_RULE_EXPANDER, function () {
            return $this->getProductAttributeDecisionRuleExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeCollectorExpanderPluginInterface>
     */
    protected function getProductAttributeCollectorExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface>
     */
    protected function getProductAttributeDecisionRuleExpanderPlugins(): array
    {
        return [];
    }
}
