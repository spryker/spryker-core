<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\ExpenseTaxWithDiscountsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\SumGrossCalculatedDiscountAmountCalculator;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToTaxInterface;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig getConfig()
 */
class DiscountCalculationConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator
     */
    public function createDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator
     */
    public function createGrandTotalWithDiscountsCalculator()
    {
        return new GrandTotalWithDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator
     */
    public function createRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\SumGrossCalculatedDiscountAmountCalculator
     */
    public function createSumGrossCalculatedDiscountAmountCalculator()
    {
        return new SumGrossCalculatedDiscountAmountCalculator();
    }

    public function createExpenseTaxWithDiscountsCalculator()
    {
        return new ExpenseTaxWithDiscountsCalculator($this->getTaxFacade());
    }

    /**
     * @return DiscountCalculationToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_TAX);
    }

}
