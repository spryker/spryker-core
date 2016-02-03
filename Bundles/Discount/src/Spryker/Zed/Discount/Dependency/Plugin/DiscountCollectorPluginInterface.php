<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountCollectorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discount
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Spryker\Zed\Discount\Business\Model\DiscountableInterface[]
     */
    public function collect(
        DiscountTransfer $discount,
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    );

}
