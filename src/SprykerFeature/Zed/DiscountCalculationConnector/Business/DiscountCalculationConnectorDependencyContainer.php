<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

class DiscountCalculationConnectorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return DiscountTotalsCalculator
     */
    public function getDiscountTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorDiscountTotalsCalculator($this->getLocator());
    }

    /**
     * @return GrandTotalWithDiscountsTotalsCalculator
     */
    public function getGrandTotalWithDiscountsTotalsCalculator()
    {
        $calculationFacade = $this->getCalculationFacade();
        $discountTotalsCalculator = $this->getDiscountTotalsCalculator();

        return $this->getFactory()->createModelCalculatorGrandTotalWithDiscountsTotalsCalculator(
            $this->getLocator(),
            $calculationFacade,
            $discountTotalsCalculator
        );
    }

    /**
     * @return RemoveAllCalculatedDiscountsCalculator
     */
    public function getRemoveAllCalculatedDiscountsCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveAllCalculatedDiscountsCalculator($this->getLocator());
    }


    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }
}
