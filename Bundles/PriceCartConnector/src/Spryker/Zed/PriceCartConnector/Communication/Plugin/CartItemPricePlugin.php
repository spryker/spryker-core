<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;

/**
 * @method PriceCartConnectorFacade getFacade()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->addGrossPriceToItems($cartChangeTransfer);
    }

}
