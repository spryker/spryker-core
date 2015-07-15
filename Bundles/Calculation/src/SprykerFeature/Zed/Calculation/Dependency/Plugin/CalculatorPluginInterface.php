<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface CalculatorPluginInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     */
    public function recalculate(CalculableInterface $calculableContainer);

}
