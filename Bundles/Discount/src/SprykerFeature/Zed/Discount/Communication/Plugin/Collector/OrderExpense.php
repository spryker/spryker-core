<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class OrderExpense extends AbstractPlugin implements
    DiscountCollectorPluginInterface
{
    /**
     * @param OrderInterface $container
     * @return OrderInterface[]
     */
    public function collect(OrderInterface $container)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->getDiscountableOrderExpenses($container);
    }
}
