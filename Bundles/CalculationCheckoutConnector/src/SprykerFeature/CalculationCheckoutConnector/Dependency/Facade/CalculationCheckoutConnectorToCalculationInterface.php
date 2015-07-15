<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\CalculationCheckoutConnector\Dependency\Facade;

use Generated\Shared\Calculation\OrderInterface;

interface CalculationCheckoutConnectorToCalculationInterface
{

    /**
     * @param OrderInterface $calculableContainer
     *
     * @return OrderInterface
     */
    public function recalculate(OrderInterface $calculableContainer);

}
