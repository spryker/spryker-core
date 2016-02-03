<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceCartConnector\Communication\PriceCartConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade getFacade()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->getFacade()->addGrossPriceToItems($change);
    }

}
