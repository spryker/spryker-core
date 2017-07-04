<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelDiscountConnector\Business\Collector\ProductLabelCollector;
use Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule\ProductLabelDecisionRule;
use Spryker\Zed\ProductLabelDiscountConnector\Business\Label\LabelProvider;
use Spryker\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorConfig getConfig()
 */
class ProductLabelDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Business\Label\LabelProviderInterface
     */
    public function createLabelProvider()
    {
        return new LabelProvider($this->getProductLabelFacade());
    }

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule\ProductLabelDecisionRuleInterface
     */
    public function createProductLabelDecisionRule()
    {
        return new ProductLabelDecisionRule($this->getDiscountFacade(), $this->getProductLabelQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Business\Collector\ProductLabelCollectorInterface
     */
    public function createProductLabelCollector()
    {
        return new ProductLabelCollector($this->createProductLabelDecisionRule());
    }

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToProductLabelInterface
     */
    protected function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelDiscountConnectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface
     */
    protected function getDiscountFacade()
    {
        return $this->getProvidedDependency(ProductLabelDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer\ProductLabelDiscountConnectorToProductLabelInterface
     */
    protected function getProductLabelQueryContainer()
    {
        return $this->getProvidedDependency(ProductLabelDiscountConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_LABEL);
    }

}
