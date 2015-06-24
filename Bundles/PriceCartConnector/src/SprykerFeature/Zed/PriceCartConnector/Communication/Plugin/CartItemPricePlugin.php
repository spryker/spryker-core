<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Communication\Plugin;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use SprykerFeature\Zed\PriceCartConnector\Communication\PriceCartConnectorDependencyContainer;
use Generated\Shared\Cart\CartItemTransfer;
use Generated\Shared\Cart\CartItemsInterface;

/**
 * @method PriceCartConnectorDependencyContainer getDependencyContainer()
 */
class CartItemPricePlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * @var PriceManagerInterface
     */
    private $priceManager;

    /**
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->priceManager = $this->getDependencyContainer()->createFacade();
    }

    /**
     * @param CartItemsInterface $items
     *
     * @return CartItemsInterface
     */
    public function expandItems(CartItemsInterface $items)
    {
        return $this->priceManager->addGrossPriceToItems($items);
    }
}
