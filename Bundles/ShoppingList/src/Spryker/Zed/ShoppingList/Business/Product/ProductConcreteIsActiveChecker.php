<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Product;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;

class ProductConcreteIsActiveChecker implements ProductConcreteIsActiveCheckerInterface
{
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'customer.account.shopping_list_item.error.product_not_active';

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     */
    public function __construct(ShoppingListToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductIsActive(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        $productConcreteTransfer = (new ProductConcreteTransfer())->setSku($shoppingListItemTransfer->getSku());
        $shoppingListPreAddItemCheckResponseTransfer = new ShoppingListPreAddItemCheckResponseTransfer();
        if ($this->productFacade->isProductConcreteActive($productConcreteTransfer)) {
            return $shoppingListPreAddItemCheckResponseTransfer
                ->setIsSuccess(true);
        }

        return $shoppingListPreAddItemCheckResponseTransfer
            ->setIsSuccess(false)
            ->addMessage(
                (new MessageTransfer())
                    ->setValue(static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE)
            );
    }
}
