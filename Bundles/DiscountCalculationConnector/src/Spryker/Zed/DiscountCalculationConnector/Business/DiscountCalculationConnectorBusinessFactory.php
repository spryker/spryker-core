<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class DiscountCalculationConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator
     */
    public function getDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator
     */
    public function getGrandTotalWithDiscountsCalculator()
    {
        return new GrandTotalWithDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator
     */
    public function getRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }

}
