<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShoppingListNote\Business\ShoppingListNoteBusinessFactory getFactory()
 */
class ShoppingListNoteFacade extends AbstractFacade implements ShoppingListNoteFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    public function getShoppingListItemNoteByIdShoppingListItem(int $idShoppingListItem): ShoppingListItemNoteTransfer
    {
        return $this->getFactory()->createShoppingListNoteReader()->getShoppingListItemNoteByIdShoppingListItem($idShoppingListItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemNoteForShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createShoppingListNoteWriter()
            ->saveShoppingListItemNoteForShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemNote(ShoppingListItemNoteTransfer $shoppingListItemNoteTransfer): void
    {
        $this->getFactory()->createShoppingListNoteWriter()->deleteShoppingListItemNoteById($shoppingListItemNoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()->createShoppingListItemExpander()->expandItem($shoppingListItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapItemCartNoteToShoppingListItemNote(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createItemToShoppingListItemMapper()
            ->mapItemCartNoteToShoppingListItemNote($itemTransfer, $shoppingListItemTransfer);
    }
}
