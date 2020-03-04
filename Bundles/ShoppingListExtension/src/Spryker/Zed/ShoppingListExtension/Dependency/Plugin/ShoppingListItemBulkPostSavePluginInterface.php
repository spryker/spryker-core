<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListExtension\Dependency\Plugin;

interface ShoppingListItemBulkPostSavePluginInterface
{
    /**
     * Specification:
     * - This plugin executes after shopping list item saving.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer[] $shoppingListItems
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer[]
     */
    public function execute(array $shoppingListItems): array;
}
