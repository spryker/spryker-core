<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Item implements CollectorInterface
{

    /**
     * @param CalculableInterface $container
     * @param DiscountCollectorTransfer $discountCollectorTransfer
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
