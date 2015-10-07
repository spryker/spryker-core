<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Item implements CollectorInterface
{

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return array
     */
    public function collect(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        $discountableItems = [];

        foreach ($container->getCalculableObject()->getItems() as $item) {
            $discountableItems[] = $item;
        }

        return $discountableItems;
    }

}
