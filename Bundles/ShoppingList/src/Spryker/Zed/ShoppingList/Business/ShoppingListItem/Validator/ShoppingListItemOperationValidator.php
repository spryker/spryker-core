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
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

abstract class ShoppingListItemOperationValidator implements ShoppingListItemOperationValidatorInterface
{
    use PermissionAwareTrait;

    protected const MAX_QUANTITY = 2147483647; // 32 bit integer
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    protected const ERROR_SHOPPING_LIST_NOT_FOUND = 'customer.account.shopping_list.error.not_found';
    protected const ERROR_SHOPPING_LIST_ITEM_NOT_FOUND = 'customer.account.shopping_list_item.error.not_found';
    protected const ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'customer.account.shopping_list.error.write_permission_required';
    protected const ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID = 'customer.account.shopping_list_item.error.quantity_not_valid';

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToMessengerFacadeInterface $messengerFacade
    ) {
        $this->productFacade = $productFacade;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    abstract public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function invalidateResponse(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemResponseTransfer->getIsSuccess() === true) {
            $this->addSuccessMessage(
                $shoppingListItemResponseTransfer->getShoppingListItem()
                ?? new ShoppingListItemTransfer()
            );
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    abstract protected function addFailedMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    abstract protected function addSuccessMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function checkShoppingListItemParent(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireFkShoppingList();

        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById(
            (new ShoppingListTransfer())->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
        );

        if (!$shoppingListTransfer) {
            $this->addFailedMessage($shoppingListItemTransfer);
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
        }

        if (!$this->findShoppingListItemById($shoppingListItemTransfer, $shoppingListTransfer)) {
            $this->addFailedMessage($shoppingListItemTransfer);
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
        }

        $shoppingListTransfer->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser());

        return $this->validatePermissionForPerformingOperation(
            $shoppingListItemTransfer,
            $shoppingListTransfer,
            $shoppingListItemResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function validatePermissionForPerformingOperation(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        if ($this->checkWritePermission($shoppingListTransfer)) {
            return true;
        }

        $this->addFailedMessage($shoppingListItemTransfer);
        $shoppingListItemResponseTransfer
            ->addError(static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
            ->setIsSuccess(false);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function validateShoppingListItemQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireQuantity();

        $quantity = $shoppingListItemTransfer->getQuantity();
        if ($quantity <= 0 || $quantity > static::MAX_QUANTITY) {
            $this->addFailedMessage($shoppingListItemTransfer);
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkWritePermission(ShoppingListTransfer $shoppingListTransfer): bool
    {
        if (!$shoppingListTransfer->getIdShoppingList()) {
            return true;
        }

        if (!$shoppingListTransfer->getIdCompanyUser()) {
            return false;
        }

        return $this->can(
            'WriteShoppingListPermissionPlugin',
            $shoppingListTransfer->getIdCompanyUser(),
            $shoppingListTransfer->getIdShoppingList()
        );
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
