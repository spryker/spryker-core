<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class PriceCartConnectorFacade extends AbstractFacade
{
    /**
     * @param ItemCollectionInterface $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function addGrossPriceToItems(ItemCollectionInterface $items)
    {
        return $this->getDependencyContainer()->createPriceManager()->addGrossPriceToItems($items);
    }
}
 