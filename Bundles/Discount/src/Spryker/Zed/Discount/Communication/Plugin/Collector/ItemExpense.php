<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Business\Model\DiscountableInterface;
use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;

/**
 * @method DiscountCommunicationFactory getFactory()
 */
class ItemExpense extends AbstractDiscountPlugin implements DiscountCollectorPluginInterface
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
        return $this->getFactory()->getDiscountFacade()
            ->getDiscountableItemExpenses($container, $discountCollectorTransfer);
    }

}
