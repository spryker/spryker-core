<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class CartToCalculationBridge implements CartToCalculationInterface
{

    /**
     * @var CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param CalculationFacade $calculationFacade
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
