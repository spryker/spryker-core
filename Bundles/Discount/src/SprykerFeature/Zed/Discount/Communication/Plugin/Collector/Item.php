<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Collector;

use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Item extends AbstractPlugin implements
    DiscountCollectorPluginInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return array
     */
    public function collect(DiscountableContainerInterface $container)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->getDiscountableItems($container);
    }
}
