<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemValidator implements ShoppingListItemValidatorInterface
{
    protected const MAX_QUANTITY = 2147483647; // 32 bit integer

    protected const ERROR_SHOPPING_LIST_NOT_FOUND = 'customer.account.shopping_list.error.not_found';
    protected const ERROR_SHOPPING_LIST_ITEM_NOT_FOUND = 'customer.account.shopping_list_item.error.not_found';
    protected const ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID = 'customer.account.shopping_list_item.error.quantity_not_valid';
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND = 'customer.account.shopping_list_item.error.product_not_found';

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface
     */
    protected $permissionValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface $permissionValidator
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListItemPermissionValidatorInterface $permissionValidator,
        ShoppingListToProductFacadeInterface $productFacade
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->permissionValidator = $permissionValidator;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function checkShoppingListItemParent(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById(
            (new ShoppingListTransfer())->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
        );

        if (!$shoppingListTransfer) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_NOT_FOUND)
                ->setIsSuccess(false);

            return $shoppingListItemResponseTransfer;
        }

        if (!$this->checkShoppingListItem($shoppingListItemTransfer, $shoppingListTransfer)) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_NOT_FOUND)
                ->setIsSuccess(false);

            return $shoppingListItemResponseTransfer;
        }

        $shoppingListTransfer->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser());

        return $this->permissionValidator->validatePermissionForPerformingOperation(
            $shoppingListTransfer,
            $shoppingListItemResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateShoppingListItemQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $quantity = $shoppingListItemTransfer->getQuantity();
        if ($quantity <= 0 || $quantity > static::MAX_QUANTITY) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);

            return $shoppingListItemResponseTransfer;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateShoppingListItemSku(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        if (!$this->productFacade->hasProductConcrete($shoppingListItemTransfer->getSku())) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND)
                ->setIsSuccess(false);

            return $shoppingListItemResponseTransfer;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): bool {
        foreach ($shoppingListTransfer->getItems() as $ownShoppingListItemTransfer) {
            if ($ownShoppingListItemTransfer->getIdShoppingListItem() === $shoppingListItemTransfer->getIdShoppingListItem()) {
                return true;
            }
        }

        return false;
    }
}
