<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class PermissionValidator implements PermissionValidatorInterface
{
    use PermissionAwareTrait;

    protected const ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'customer.account.shopping_list.error.write_permission_required';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    public function validatePermissionForPerformingOperation(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        if ($this->checkWritePermission($shoppingListTransfer)) {
            return true;
        }

        $shoppingListItemResponseTransfer
            ->addError(static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
            ->setIsSuccess(false);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    public function checkWritePermission(ShoppingListTransfer $shoppingListTransfer): bool
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
}
