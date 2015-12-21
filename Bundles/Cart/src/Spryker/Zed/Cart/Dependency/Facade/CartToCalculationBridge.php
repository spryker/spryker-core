<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class CartToCalculationBridge implements CartToCalculationInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacade $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        return $this->calculationFacade->recalculate($calculableContainer);
    }

}
