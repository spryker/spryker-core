<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\CartItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\CartItemInterfaceTransfer;

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
