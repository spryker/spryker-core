<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Communication\Plugin;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory;

/**
 * @method CalculationFacade getFacade()
 * @method CalculationCommunicationFactory getFactory()
 */
class ItemPriceToPayCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        $this->getFacade()->recalculateItemPriceToPay($calculableContainer);
    }

}
