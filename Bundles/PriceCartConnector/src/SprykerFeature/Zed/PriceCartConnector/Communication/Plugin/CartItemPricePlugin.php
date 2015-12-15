<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use Spryker\Zed\PriceCartConnector\Communication\PriceCartConnectorDependencyContainer;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @var PriceManagerInterface
     */
    private $priceManager;

    public function __construct()
    {
        $this->priceManager = $this->getDependencyContainer()->createFacade();
    }

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->priceManager->addGrossPriceToItems($change);
    }

}
