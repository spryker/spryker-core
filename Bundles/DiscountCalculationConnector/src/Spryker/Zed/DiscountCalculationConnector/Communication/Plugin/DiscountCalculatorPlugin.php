<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication\Plugin;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\DiscountCalculationConnector\Communication\DiscountCalculationConnectorCommunicationFactory;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade;

/**
 * @method DiscountCalculationConnectorCommunicationFactory getFactory()
 * @method DiscountCalculationConnectorFacade getFacade()
 */
class DiscountCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return array
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        return $this->getFactory()->getDiscountFacade()->calculateDiscounts($calculableContainer);
    }

}
