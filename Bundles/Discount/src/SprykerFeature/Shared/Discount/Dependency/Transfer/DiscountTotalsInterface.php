<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountTotalsInterface
{

    /**
     * @return mixed
     */
    public function getSubtotalWithoutItemExpenses();

}
