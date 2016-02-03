<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade getFacade()
 */
class DiscountCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return array
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        return $this->getFactory()->getDiscountFacade()->calculateDiscounts($calculableContainer);
    }

}
