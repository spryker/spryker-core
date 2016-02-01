<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class Item implements CollectorInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function collect(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        $discountableItems = [];

        foreach ($container->getCalculableObject()->getItems() as $item) {
            $discountableItems[] = $item;
        }

        return $discountableItems;
    }

}
