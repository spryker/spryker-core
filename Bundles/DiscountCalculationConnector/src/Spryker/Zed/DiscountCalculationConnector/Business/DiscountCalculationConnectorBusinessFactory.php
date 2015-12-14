<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationInterface;
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
     * @return DiscountCalculationToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_CALCULATOR);
    }

}
