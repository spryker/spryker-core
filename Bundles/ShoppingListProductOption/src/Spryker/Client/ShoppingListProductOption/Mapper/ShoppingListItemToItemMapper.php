<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface;

class ShoppingListItemToItemMapper implements ShoppingListItemToItemMapperInterface
{
    /**
     * @var \Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface $cartClient
     */
    public function __construct(ShoppingListProductOptionToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapShoppingListItemProductOptionToItemProductOption(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $quoteItemTransfer = $this->cartClient->findQuoteItem($quoteTransfer, $itemTransfer->getSku());

        if ($quoteItemTransfer) {
            $itemTransfer->setProductOptions($shoppingListItemTransfer->getProductOptions());
        }

        return $itemTransfer;
    }
}
