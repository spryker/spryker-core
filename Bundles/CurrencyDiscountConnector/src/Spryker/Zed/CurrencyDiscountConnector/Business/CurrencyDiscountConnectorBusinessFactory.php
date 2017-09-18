<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Business;

use Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyCollector;
use Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyDecisionRule;
use Spryker\Zed\CurrencyDiscountConnector\CurrencyDiscountConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CurrencyDiscountConnector\CurrencyDiscountConnectorConfig getConfig()
 */
class CurrencyDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyCollectorInterface
     */
    public function createCurrencyCollector()
    {
        return new CurrencyCollector($this->createCurrencyDecisionRule());
    }

    /**
     * @return \Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyDecisionRuleInterface
     */
    public function createCurrencyDecisionRule()
    {
        return new CurrencyDecisionRule($this->getCurrencyFacade(), $this->getDiscountFacade());
    }

    /**
     * @return \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(CurrencyDiscountConnectorDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToDiscountInterface
     */
    protected function getDiscountFacade()
    {
        return $this->getProvidedDependency(CurrencyDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

}
