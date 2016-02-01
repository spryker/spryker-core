<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountCalculationToDiscountInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return mixed
     */
    public function calculateDiscounts(CalculableInterface $calculableContainer);

}
