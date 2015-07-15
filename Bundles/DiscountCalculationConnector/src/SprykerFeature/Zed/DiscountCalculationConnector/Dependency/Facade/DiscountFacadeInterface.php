<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountFacadeInterface
{

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return mixed
     */
    public function calculateDiscounts(CalculableInterface $calculableContainer);

}
