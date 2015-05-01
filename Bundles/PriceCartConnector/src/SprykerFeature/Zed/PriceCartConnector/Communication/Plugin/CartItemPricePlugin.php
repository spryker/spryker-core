<?php

namespace SprykerFeature\Zed\PriceCartConnector\Communication\Plugin;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface;
use SprykerFeature\Zed\PriceCartConnector\Communication\PriceCartConnectorDependencyContainer;

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
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function expandItems(ItemCollectionInterface $items)
    {
        return $this->priceManager->addGrossPriceToItems($items);
    }
}
