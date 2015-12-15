<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountFacadeInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return mixed
     */
    public function calculateDiscounts(CalculableInterface $calculableContainer);

}
