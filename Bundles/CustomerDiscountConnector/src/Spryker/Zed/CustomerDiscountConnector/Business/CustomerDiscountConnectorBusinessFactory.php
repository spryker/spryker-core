<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Business;

use Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerDecisionRuleChecker;
use Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerDecisionRuleCheckerInterface;
use Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerOrderCountDecisionRuleChecker;
use Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerOrderCountDecisionRuleCheckerInterface;
use Spryker\Zed\CustomerDiscountConnector\Business\Saver\CustomerDiscountSaver;
use Spryker\Zed\CustomerDiscountConnector\Business\Saver\CustomerDiscountSaverInterface;
use Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorDependencyProvider;
use Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig getConfig()
 */
class CustomerDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerDecisionRuleCheckerInterface
     */
    public function createCustomerDecisionRuleChecker(): CustomerDecisionRuleCheckerInterface
    {
        return new CustomerDecisionRuleChecker($this->getDiscountFacade());
    }

    /**
     * @return \Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerOrderCountDecisionRuleCheckerInterface
     */
    public function createCustomerOrderCountDecisionRuleChecker(): CustomerOrderCountDecisionRuleCheckerInterface
    {
        return new CustomerOrderCountDecisionRuleChecker(
            $this->getDiscountFacade(),
            $this->getConfig(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomerDiscountConnector\Business\Saver\CustomerDiscountSaverInterface
     */
    public function createCustomerDiscountSaver(): CustomerDiscountSaverInterface
    {
        return new CustomerDiscountSaver(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface
     */
    public function getDiscountFacade(): CustomerDiscountConnectorToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(CustomerDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
