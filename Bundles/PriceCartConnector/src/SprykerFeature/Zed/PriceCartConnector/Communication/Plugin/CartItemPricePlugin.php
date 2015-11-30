<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
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
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->priceManager->addGrossPriceToItems($change);
    }

}
