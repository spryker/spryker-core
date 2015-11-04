<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Item extends AbstractDiscountPlugin implements DiscountCollectorPluginInterface
{

    /**
     * @param DiscountInterface $discount
     * @param CalculableInterface $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return \SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface[]
     */
    public function collect(
        DiscountInterface $discount,
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->getDiscountFacade()
            ->getDiscountableItems($container, $discountCollectorTransfer);
    }

}
