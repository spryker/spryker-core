<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade getFacade()
 * @method \Spryker\Zed\ProductCartConnector\Communication\ProductCartConnectorCommunicationFactory getFactory()
 */
class ProductCartPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        $this->getFacade()->expandItems($cartChangeTransfer);

        return $cartChangeTransfer;
    }

}
