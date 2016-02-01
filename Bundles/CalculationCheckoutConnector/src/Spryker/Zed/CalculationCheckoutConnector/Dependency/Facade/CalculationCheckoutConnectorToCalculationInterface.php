<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface CalculationCheckoutConnectorToCalculationInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer);

}
