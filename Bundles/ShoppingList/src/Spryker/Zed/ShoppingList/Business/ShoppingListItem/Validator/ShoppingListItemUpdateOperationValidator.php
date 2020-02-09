<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface;

class ShoppingListItemUpdateOperationValidator implements ShoppingListItemUpdateOperationValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface
     */
    protected $shoppingListItemValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface
     */
    protected $messageAdder;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\PermissionValidatorInterface
     */
    protected $permissionValidator;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface $shoppingListItemValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface $messageAdder
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\PermissionValidatorInterface $permissionValidator
     */
    public function __construct(
        ShoppingListItemValidatorInterface $shoppingListItemValidator,
        MessageAdderInterface $messageAdder,
        PermissionValidatorInterface $permissionValidator
    ) {
        $this->shoppingListItemValidator = $shoppingListItemValidator;
        $this->messageAdder = $messageAdder;
        $this->permissionValidator = $permissionValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function invalidateResponse(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemResponseTransfer->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemUpdateSuccessMessage();
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $isShoppingListItemQuantityValid = $this->shoppingListItemValidator->validateShoppingListItemQuantity(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$isShoppingListItemQuantityValid) {
            $this->messageAdder->addShoppingListItemUpdateFailedMessage();
        }
        $isShoppingListItemParenValid = $this->shoppingListItemValidator->validateShoppingListItemQuantity(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$isShoppingListItemParenValid) {
            $this->messageAdder->addShoppingListItemUpdateFailedMessage();
        }

        return $isShoppingListItemParenValid && $isShoppingListItemQuantityValid;
    }
}
