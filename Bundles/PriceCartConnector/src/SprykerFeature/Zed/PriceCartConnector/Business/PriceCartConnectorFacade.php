<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Cart\CartItemTransfer;
use Generated\Shared\Cart\CartItemsInterface;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class PriceCartConnectorFacade extends AbstractFacade
{
    /**
     * @param CartItemsInterface $items
     *
     * @return CartItemsInterface
     */
    public function addGrossPriceToItems(CartItemsInterface $items)
    {
        return $this->getDependencyContainer()->createPriceManager()->addGrossPriceToItems($items);
    }
}
