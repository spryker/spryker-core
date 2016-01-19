<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade getFacade()
 * @method \Spryker\Zed\ProductOptionCartConnector\Communication\ProductOptionCartConnectorCommunicationFactory getFactory()
 */
class CartItemProductOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        $this->getFacade()->expandProductOptions($cartChangeTransfer);

        return $cartChangeTransfer;
    }

}
