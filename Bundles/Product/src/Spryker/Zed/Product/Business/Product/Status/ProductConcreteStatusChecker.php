<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteStatusChecker implements ProductConcreteStatusCheckerInterface
{
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'customer.account.shopping_list_item.error.product_not_active';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->productRepository->isProductConcreteActive($productConcreteTransfer);
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

        if ($this->isActive($productConcreteTransfer)) {
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
