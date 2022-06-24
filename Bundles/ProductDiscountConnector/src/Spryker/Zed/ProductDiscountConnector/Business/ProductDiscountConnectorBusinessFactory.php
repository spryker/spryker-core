<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscountConnector\Business\Attribute\AttributeProvider;
use Spryker\Zed\ProductDiscountConnector\Business\Collector\ProductAttributeCollector;
use Spryker\Zed\ProductDiscountConnector\Business\DecisionRule\ProductAttributeDecisionRule;
use Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorConfig getConfig()
 */
class ProductDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Business\DecisionRule\ProductAttributeDecisionRule
     */
    public function createProductAttributeDecisionRule()
    {
        return new ProductAttributeDecisionRule(
            $this->getProductFacade(),
            $this->getDiscountFacade(),
            $this->getLocaleFacade(),
            $this->getProductAttributeDecisionRuleExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Business\Collector\ProductAttributeCollector
     */
    public function createProductAttributeCollector()
    {
        return new ProductAttributeCollector(
            $this->createProductAttributeDecisionRule(),
            $this->getProductAttributeCollectorExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Business\Attribute\AttributeProvider
     */
    public function createAttributeProvider()
    {
        return new AttributeProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeCollectorExpanderPluginInterface>
     */
    public function getProductAttributeCollectorExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::PLUGINS_PRODUCT_ATTRIBUTE_COLLECTOR_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface>
     */
    public function getProductAttributeDecisionRuleExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::PLUGINS_PRODUCT_ATTRIBUTE_DECISION_RULE_EXPANDER);
    }
}
