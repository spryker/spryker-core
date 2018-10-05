<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListNote\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListNote\Dependency\Client\ShoppingListNoteToCartClientInterface;

class ShoppingListItemToItemMapper implements ShoppingListItemToItemMapperInterface
{
    /**
     * @var \Spryker\Client\ShoppingListNote\Dependency\Client\ShoppingListNoteToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ShoppingListNote\Dependency\Client\ShoppingListNoteToCartClientInterface $cartClient
     */
    public function __construct(ShoppingListNoteToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapShoppingListItemNoteToItemCartNote(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $quoteItemTransfer = $this->cartClient->findQuoteItem($quoteTransfer, $itemTransfer->getSku());

        $shoppingListItemNote = $shoppingListItemTransfer->getShoppingListItemNote()->getNote();

        if (!$quoteItemTransfer && !$shoppingListItemNote) {
            return $itemTransfer;
        }

        if ($quoteItemTransfer && ($shoppingListItemNote || ($quoteItemTransfer->getCartNote() && !$shoppingListItemNote))) {
            $itemTransfer->setGroupKeyPrefix(uniqid('', true));
        }

        if ($shoppingListItemNote) {
            $itemTransfer->setCartNote($shoppingListItemNote);
        }

        return $itemTransfer;
    }
}
