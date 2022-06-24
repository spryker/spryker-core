<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRule;
use Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRuleInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Business\Expander\BundledProductDiscountableItemCollectionExpander;
use Spryker\Zed\ProductBundleDiscountConnector\Business\Expander\BundledProductDiscountableItemCollectionExpanderInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeInterface;
use Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeInterface;
use Spryker\Zed\ProductBundleDiscountConnector\ProductBundleDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundleDiscountConnector\ProductBundleDiscountConnectorConfig getConfig()
 */
class ProductBundleDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleDiscountConnector\Business\Expander\BundledProductDiscountableItemCollectionExpanderInterface
     */
    public function createProductAttributeDiscountableItemsCollectionExpander(): BundledProductDiscountableItemCollectionExpanderInterface
    {
        return new BundledProductDiscountableItemCollectionExpander(
            $this->createProductBundleAttributeDecisionRule(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRuleInterface
     */
    public function createProductBundleAttributeDecisionRule(): ProductBundleAttributeDecisionRuleInterface
    {
        return new ProductBundleAttributeDecisionRule(
            $this->getDiscountFacade(),
            $this->getLocaleFacade(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToProductFacadeInterface
     */
    public function getProductFacade(): ProductBundleDiscountConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleDiscountConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductBundleDiscountConnectorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleDiscountConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductBundleDiscountConnector\Dependency\Facade\ProductBundleDiscountConnectorToDiscountFacadeInterface
     */
    public function getDiscountFacade(): ProductBundleDiscountConnectorToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
