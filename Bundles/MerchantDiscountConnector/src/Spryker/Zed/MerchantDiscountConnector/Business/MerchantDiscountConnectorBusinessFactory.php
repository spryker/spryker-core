<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleChecker;
use Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleCheckerInterface;
use Spryker\Zed\MerchantDiscountConnector\Business\Collector\DiscountableItemCollector;
use Spryker\Zed\MerchantDiscountConnector\Business\Collector\DiscountableItemCollectorInterface;
use Spryker\Zed\MerchantDiscountConnector\Business\Reader\MerchantReader;
use Spryker\Zed\MerchantDiscountConnector\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeInterface;
use Spryker\Zed\MerchantDiscountConnector\MerchantDiscountConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantDiscountConnector\MerchantDiscountConnectorConfig getConfig()
 */
class MerchantDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleCheckerInterface
     */
    public function createMerchantReferenceDecisionRuleChecker(): MerchantReferenceDecisionRuleCheckerInterface
    {
        return new MerchantReferenceDecisionRuleChecker($this->getDiscountFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantDiscountConnector\Business\Collector\DiscountableItemCollectorInterface
     */
    public function createDiscountableItemCollector(): DiscountableItemCollectorInterface
    {
        return new DiscountableItemCollector($this->createMerchantReferenceDecisionRuleChecker());
    }

    /**
     * @return \Spryker\Zed\MerchantDiscountConnector\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader($this->getMerchantFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeInterface
     */
    public function getDiscountFacade(): MerchantDiscountConnectorToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantDiscountConnectorToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDiscountConnectorDependencyProvider::FACADE_MERCHANT);
    }
}
