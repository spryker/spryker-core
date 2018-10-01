<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote\ShoppingListItemNoteReaderInterface
     */
    protected $shoppingLisItemNoteReader;

    /**
     * @param \Spryker\Zed\ShoppingListNote\Business\ShoppingListItemNote\ShoppingListItemNoteReaderInterface $shoppingLisItemNoteReader
     */
    public function __construct(ShoppingListItemNoteReaderInterface $shoppingLisItemNoteReader)
    {
        $this->shoppingLisItemNoteReader = $shoppingLisItemNoteReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemNoteTransfer = $this->shoppingLisItemNoteReader
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);

        return $shoppingListItemTransfer;
    }
}
