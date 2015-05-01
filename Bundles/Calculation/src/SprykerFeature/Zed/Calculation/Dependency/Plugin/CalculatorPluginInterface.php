<?php

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;

interface CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer);
}
