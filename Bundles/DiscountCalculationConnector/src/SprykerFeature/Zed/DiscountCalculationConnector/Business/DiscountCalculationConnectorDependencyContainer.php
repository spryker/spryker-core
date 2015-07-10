<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Business;

use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

class DiscountCalculationConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return DiscountTotalsCalculator
     */
    public function getDiscountTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorDiscountTotalsCalculator();
    }

    /**
     * @return GrandTotalWithDiscountsTotalsCalculator
     */
    public function getGrandTotalWithDiscountsTotalsCalculator()
    {
        $calculationFacade = $this->getCalculationFacade();
        $discountTotalsCalculator = $this->getDiscountTotalsCalculator();

        return $this->getFactory()->createModelCalculatorGrandTotalWithDiscountsTotalsCalculator(
            $calculationFacade,
            $discountTotalsCalculator
        );
    }

    /**
     * @return RemoveAllCalculatedDiscountsCalculator
     */
    public function getRemoveAllCalculatedDiscountsCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }

}
