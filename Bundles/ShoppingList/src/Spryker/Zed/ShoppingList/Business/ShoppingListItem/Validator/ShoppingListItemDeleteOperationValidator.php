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

class ShoppingListItemDeleteOperationValidator implements ShoppingListItemDeleteOperationValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface
     */
    protected $messageAdder;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface
     */
    protected $shoppingListItemValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface
     */
    protected $permissionValidator;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface $shoppingListItemValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface $messageAdder
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface $permissionValidator
     */
    public function __construct(
        ShoppingListItemValidatorInterface $shoppingListItemValidator,
        MessageAdderInterface $messageAdder,
        ShoppingListItemPermissionValidatorInterface $permissionValidator
    ) {
        $this->messageAdder = $messageAdder;
        $this->shoppingListItemValidator = $shoppingListItemValidator;
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
            $this->messageAdder->addShoppingListItemDeleteSuccessMessage();
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $shoppingListItemResponseTransferWithValidatedParent = $this->shoppingListItemValidator->checkShoppingListItemParent(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );

        if (!$shoppingListItemResponseTransferWithValidatedParent->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemDeleteFailedMessage();

            return $shoppingListItemResponseTransferWithValidatedParent;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }
}
