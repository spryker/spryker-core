<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleChecker;
use Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleCheckerInterface;
use Spryker\Zed\ProductOfferDiscountConnector\Business\Collector\ProductOfferDiscountableItemCollector;
use Spryker\Zed\ProductOfferDiscountConnector\Business\Collector\ProductOfferDiscountableItemCollectorInterface;
use Spryker\Zed\ProductOfferDiscountConnector\Dependency\Facade\ProductOfferDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\ProductOfferDiscountConnector\ProductOfferDiscountConnectorDependencyProvider;

class ProductOfferDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleCheckerInterface
     */
    public function createProductOfferDecisionRuleChecker(): ProductOfferDecisionRuleCheckerInterface
    {
        return new ProductOfferDecisionRuleChecker($this->getDiscountFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferDiscountConnector\Business\Collector\ProductOfferDiscountableItemCollectorInterface
     */
    public function createProductOfferDiscountableItemCollector(): ProductOfferDiscountableItemCollectorInterface
    {
        return new ProductOfferDiscountableItemCollector($this->createProductOfferDecisionRuleChecker());
    }

    /**
     * @return \Spryker\Zed\ProductOfferDiscountConnector\Dependency\Facade\ProductOfferDiscountConnectorToDiscountFacadeInterface
     */
    public function getDiscountFacade(): ProductOfferDiscountConnectorToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
