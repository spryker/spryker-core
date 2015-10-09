<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class ItemProductOption implements CollectorInterface
{
    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorInterface $discountCollectorTransfer)
    {
        $discountableOptions = [];
        foreach ($container->getCalculableObject()->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $discountableOptions[] = $productOptionTransfer;
            }
        }

        return $discountableOptions;
    }
}
