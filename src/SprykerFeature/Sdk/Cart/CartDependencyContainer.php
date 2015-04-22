<?php

namespace SprykerFeature\Sdk\Cart;

use Generated\Sdk\Ide\FactoryAutoCompletion\Cart;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Cart\Helper\CartItemCreator;
use SprykerFeature\Sdk\Cart\Helper\CatalogHelper;

class CartDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Cart
     */
    protected $factory;

    /**
     * @return CartItemCreator
     */
    public function createCartItemCreator()
    {
        return $this->getFactory()->createHelperCartItemCreator($this->getLocator());
    }

    /**
     * @return CatalogHelper
     */
    public function createCatalogHelper()
    {
        $catalogModel = $this->getLocator()->catalog()->sdk()->createCatalogModel();

        return $this->getFactory()->createHelperCatalogHelper($catalogModel);
    }
}
