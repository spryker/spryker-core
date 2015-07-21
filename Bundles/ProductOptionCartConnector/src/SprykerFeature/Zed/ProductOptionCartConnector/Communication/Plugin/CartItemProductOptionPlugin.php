<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\Manager\ProductOptionManagerInterface;
use SprykerFeature\Zed\ProductOptionCartConnector\Communication\ProductOptionCartConnectorDependencyContainer;

/**
 * @method ProductOptionCartConnectorDependencyContainer getDependencyContainer()
 */
class CartItemProductOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @var ProductOptionManagerInterface
     */
    private $productOptionManager;

    /**
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->productOptionManager = $this->getDependencyContainer()->createFacade();
    }

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        return $this->productOptionManager->expandProductOptions($change);
    }
}
