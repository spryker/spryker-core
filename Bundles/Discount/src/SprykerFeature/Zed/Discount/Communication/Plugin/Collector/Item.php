<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Item extends AbstractPlugin implements DiscountCollectorPluginInterface
{
    /**
     * @ param OrderInterface $container
     * @param CalculableInterface $container
     * @return array
     */
    public function collect(CalculableInterface $container)
    //public function collect(OrderInterface $container)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->getDiscountableItems($container);
    }
}
