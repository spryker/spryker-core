<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class ItemProductOption implements CollectorInterface
{

    /**
     * @param CalculableInterface $container
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return OrderTransfer[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer)
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
