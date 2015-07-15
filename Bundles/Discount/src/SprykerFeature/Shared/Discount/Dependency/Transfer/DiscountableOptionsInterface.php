<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountableOptionsInterface
{

    /**
     * @return DiscountItemInterface[]
     */
    public function getDiscounts();

    /**
     * @param \ArrayObject $collection
     */
    public function setDiscounts(\ArrayObject $collection);

}
