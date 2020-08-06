<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListItemDeleteOperationValidator implements ShoppingListItemDeleteOperationValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface
     */
    protected $shoppingListItemValidator;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface $shoppingListItemValidator
     */
    public function __construct(ShoppingListItemValidatorInterface $shoppingListItemValidator)
    {
        $this->shoppingListItemValidator = $shoppingListItemValidator;
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
        $shoppingListItemResponseTransferWithValidatedParent = $this->shoppingListItemValidator->checkShoppingListItemParent(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );

        if (!$shoppingListItemResponseTransferWithValidatedParent->getIsSuccess()) {
            return $shoppingListItemResponseTransferWithValidatedParent;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }
}
