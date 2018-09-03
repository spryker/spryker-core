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

        if ($quoteItemTransfer && $this->haveSameProductOptions($quoteItemTransfer, $shoppingListItemTransfer)) {
            $itemTransfer->setGroupKey($quoteItemTransfer->getGroupKey());
        }

        $itemTransfer->setProductOptions($shoppingListItemTransfer->getProductOptions());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return bool
     */
    protected function haveSameProductOptions(ItemTransfer $quoteItemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): bool
    {
        if ($quoteItemTransfer->getProductOptions()->count() !== $shoppingListItemTransfer->getProductOptions()->count()) {
            return false;
        }

        $quoteItemProductOptions = [];
        foreach ($quoteItemTransfer->getProductOptions() as $quoteProductOptionTransfer) {
            $quoteItemProductOptions[$quoteProductOptionTransfer->getIdProductOptionValue()] = $quoteProductOptionTransfer->getIdProductOptionValue();
        }

        $shoppingListItemProductOptions = [];
        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $shoppingListItemProductOptions[$productOptionTransfer->getIdProductOptionValue()] = $productOptionTransfer->getIdProductOptionValue();
        }

        return empty(array_diff_key($quoteItemProductOptions, $shoppingListItemProductOptions));
    }
}
