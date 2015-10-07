<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

/**
 * @method DiscountFacade getFacade()
 */
class Aggregate extends AbstractPlugin implements DiscountCollectorPluginInterface
{
    /**
     * @param DiscountInterface          $discount
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return  DiscountableInterface[]
     */
    public function collect(
        DiscountInterface $discount,
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getFacade()->getDiscountableItemsFromCollectorAggregate($container, $discountCollectorTransfer);
    }
}
