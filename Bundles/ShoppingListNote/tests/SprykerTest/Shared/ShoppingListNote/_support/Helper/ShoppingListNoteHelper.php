<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ShoppingListNote\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShoppingListItemBuilder;
use Generated\Shared\DataBuilder\ShoppingListItemNoteBuilder;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShoppingListNoteHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function haveShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        $shoppingListItemTransfer = (new ShoppingListItemBuilder($seed))->build();

        return $this->getLocator()->shoppingList()->facade()->addItem($shoppingListItemTransfer);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function haveShoppingListItemNote(array $seed = []): ShoppingListItemNoteTransfer
    {
        $shoppingListItemNoteTransfer = (new ShoppingListItemNoteBuilder($seed))->build();

        return $this->getLocator()->shoppingListNote()->facade()->saveShoppingListItemNote($shoppingListItemNoteTransfer);
    }
}
