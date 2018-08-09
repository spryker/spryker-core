<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Dependency\Facade;

class ShoppingListProductOptionToProductOptionFacadeBridge implements ShoppingListProductOptionToProductOptionFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @return void
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem()
    {
       //todo: add method
    }
}
