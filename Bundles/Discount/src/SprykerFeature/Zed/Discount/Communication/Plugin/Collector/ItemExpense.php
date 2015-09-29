<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class ItemExpense extends AbstractPlugin implements DiscountCollectorPluginInterface
{

    /**
     * @param DiscountInterface $discount
     * @param CalculableInterface $container
     *
     * @return DiscountableInterface[]
     */
    public function collect(DiscountInterface $discount, CalculableInterface $container)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->getDiscountableItemExpenses($container);
    }

}
