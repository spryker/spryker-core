<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface;

class ShoppingListItemUpdateOperationValidator implements ShoppingListItemUpdateOperationValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface
     */
    protected $shoppingListItemValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface
     */
    protected $messageAdder;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface $shoppingListItemValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface $messageAdder
     */
    public function __construct(
        ShoppingListItemValidatorInterface $shoppingListItemValidator,
        ShoppingListItemMessageAdderInterface $messageAdder
    ) {
        $this->shoppingListItemValidator = $shoppingListItemValidator;
        $this->messageAdder = $messageAdder;
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
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $shoppingListItemResponseTransferWithValidatedQuantity = $this->shoppingListItemValidator
            ->validateShoppingListItemQuantity($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
        if (!$shoppingListItemResponseTransferWithValidatedQuantity->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemUpdateFailedMessage();

            return $shoppingListItemResponseTransferWithValidatedQuantity;
        }

        $shoppingListItemResponseTransferWithValidatedParent = $this->shoppingListItemValidator
            ->validateShoppingListItemQuantity($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
        if (!$shoppingListItemResponseTransferWithValidatedParent->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemUpdateFailedMessage();

            return $shoppingListItemResponseTransferWithValidatedParent;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }
}
