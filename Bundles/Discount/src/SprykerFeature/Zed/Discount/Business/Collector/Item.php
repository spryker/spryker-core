<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Item implements CollectorInterface
{

    /**
     * @param CalculableInterface $container
     *
     * @return array
     */
    public function collect(CalculableInterface $container)
    {
        $discountableItems = [];

        foreach ($container->getCalculableObject()->getItems() as $item) {
            $discountableItems[] = $item;
        }

        return $discountableItems;
    }

}
