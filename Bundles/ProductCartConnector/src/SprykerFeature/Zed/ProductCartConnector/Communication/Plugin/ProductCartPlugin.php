<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductCartConnector\Business\Manager\ProductManagerInterface;
use SprykerFeature\Zed\ProductCartConnector\Communication\ProductCartConnectorDependencyContainer;

/**
 * @method ProductCartConnectorDependencyContainer getDependencyContainer()
 */
class ProductCartPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @var ProductManagerInterface
     */
    private $productManager;

    /**
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->productManager = $this->getDependencyContainer()->createFacade();
    }

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        return $this->productManager->expandItems($change);
    }

}
