<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class DiscountCalculationConnectorDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return DiscountTotalsCalculator
     */
    public function getDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return GrandTotalWithDiscountsTotalsCalculator
     */
    public function getGrandTotalWithDiscountsTotalsCalculator()
    {
        $calculationFacade = $this->getCalculationFacade();
        $discountTotalsCalculator = $this->getDiscountTotalsCalculator();

        return new GrandTotalWithDiscountsTotalsCalculator(
            $calculationFacade,
            $discountTotalsCalculator
        );
    }

    /**
     * @return RemoveAllCalculatedDiscountsCalculator
     */
    public function getRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }

}
