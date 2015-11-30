<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Generated\Shared\Transfer\DiscountTransfer;

/**
 * @method DiscountFacade getFacade()
 */
class ItemProductOption extends AbstractDiscountPlugin implements DiscountCollectorPluginInterface
{

    /**
     * @param DiscountTransfer $discount
     * @param CalculableInterface $container
     * @param DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return DiscountableInterface[]
     */
    public function collect(
        DiscountTransfer $discount,
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFacade()->getDiscountableItemProductOptions($container, $discountCollectorTransfer);
    }

}
