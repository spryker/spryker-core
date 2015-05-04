<?php

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;

interface CalculatorPluginInterface
{
    /**
     * @param CalculableContainerInterface $calculableContainer
     */
    public function recalculate(CalculableContainerInterface $calculableContainer);
}
