<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig;

/**
 * @method DiscountCalculationConnectorConfig getConfig()
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
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsTotalsCalculator
     */
    public function createGrandTotalWithDiscountsTotalsCalculator()
    {
        $calculationFacade = $this->getCalculationFacade();
        $discountTotalsCalculator = $this->createDiscountTotalsCalculator();

        return new GrandTotalWithDiscountsTotalsCalculator(
            $calculationFacade,
            $discountTotalsCalculator
        );
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\RemoveAllCalculatedDiscountsCalculator
     */
    public function createRemoveAllCalculatedDiscountsCalculator()
    {
        return new RemoveAllCalculatedDiscountsCalculator();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_CALCULATOR);
    }

}
