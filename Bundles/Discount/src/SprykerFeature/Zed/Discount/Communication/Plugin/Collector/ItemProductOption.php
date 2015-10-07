<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Discount\DiscountInterface;

/**
 * @method DiscountFacade getFacade()
 */
class ItemProductOption extends AbstractPlugin implements DiscountCollectorPluginInterface
{

    /**
     * @param DiscountInterface          $discount
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return DiscountableInterface[]
     */
    public function collect(
        DiscountInterface $discount,
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getFacade()->getDiscountableItemProductOptions($container, $discountCollectorTransfer);
    }
}
