<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;
use Spryker\Zed\PriceCartConnector\Communication\PriceCartConnectorCommunicationFactory;

/**
 * @method PriceCartConnectorCommunicationFactory getFactory()
 * @method PriceCartConnectorFacade getFacade()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->getFacade()->addGrossPriceToItems($change);
    }

}
