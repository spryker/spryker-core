<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use Spryker\Zed\PriceCartConnector\Communication\PriceCartConnectorCommunicationFactory;

/**
 * @method PriceCartConnectorCommunicationFactory getFactory()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @var PriceManagerInterface
     */
    private $priceManager;

    public function __construct()
    {
        $this->priceManager = $this->getFactory()->createFacade();
    }

    /**
     * @param CartChangeTransfer $change
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $change)
    {
        return $this->priceManager->addGrossPriceToItems($change);
    }

}
