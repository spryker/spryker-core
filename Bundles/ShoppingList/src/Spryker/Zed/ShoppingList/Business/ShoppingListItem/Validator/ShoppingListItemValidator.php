<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemValidator implements ShoppingListItemValidatorInterface
{
    use PermissionAwareTrait;

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
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\PermissionValidatorInterface
     */
    protected $permissionValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\PermissionValidatorInterface $permissionValidator
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        PermissionValidatorInterface $permissionValidator,
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
     * @return bool
     */
    public function checkShoppingListItemParent(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireFkShoppingList();

        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById(
            (new ShoppingListTransfer())->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
        );

        if (!$shoppingListTransfer) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
        }

        if (!$this->findShoppingListItemById($shoppingListItemTransfer, $shoppingListTransfer)) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
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
     * @return bool
     */
    public function validateShoppingListItemQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireQuantity();

        $quantity = $shoppingListItemTransfer->getQuantity();
        if ($quantity <= 0 || $quantity > static::MAX_QUANTITY) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    public function validateShoppingListItemSku(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireSku();

        if (!$this->productFacade->hasProductConcrete($shoppingListItemTransfer->getSku())) {
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|null
     */
    protected function findShoppingListItemById(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ?ShoppingListItemTransfer {
        foreach ($shoppingListTransfer->getItems() as $ownShoppingListItemTransfer) {
            if ($ownShoppingListItemTransfer->getIdShoppingListItem() === $shoppingListItemTransfer->getIdShoppingListItem()) {
                return $ownShoppingListItemTransfer;
            }
        }

        return null;
    }
}
