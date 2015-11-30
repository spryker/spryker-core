<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface CollectorInterface
{

    /**
     * @param CalculableInterface $container
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return OrderTransfer[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

}
