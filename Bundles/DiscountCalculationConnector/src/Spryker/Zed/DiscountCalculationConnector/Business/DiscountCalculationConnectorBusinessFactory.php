<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;

class DiscountCalculationConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return DiscountTotalsCalculator
     */
    public function getDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return GrandTotalWithDiscountsCalculator
     */
    public function getGrandTotalWithDiscountsCalculator()
    {
        return new GrandTotalWithDiscountsCalculator();
    }

    /**
     * @return RemoveAllCalculatedDiscountsCalculator
     */
    public function getRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }
}
