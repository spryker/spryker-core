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
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorDependencyProvider;
use Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface;

/**
 * @method ProductDiscountConnectorQueryContainerInterface getQueryContainer()
 */
class ProductDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return ProductAttributeDecisionRule
     */
    public function createProductAttributeDecisionRule()
    {
        return new ProductAttributeDecisionRule(
            $this->getProductFacade(),
            $this->getDiscountFacade()
        );
    }

    /**
     * @return ProductAttributeCollector
     */
    public function createProductAttributeCollector()
    {
        return new ProductAttributeCollector($this->createProductAttributeDecisionRule());
    }

    /**
     * @return \Spryker\Zed\ProductDiscountConnector\Business\Attribute\AttributeProvider
     */
    public function createAttributeProvider()
    {
        return new AttributeProvider($this->getQueryContainer());
    }

    /**
     * @return ProductDiscountConnectorToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return ProductDiscountConnectorToDiscountInterface
     */
    protected function getDiscountFacade()
    {
        return $this->getProvidedDependency(ProductDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
