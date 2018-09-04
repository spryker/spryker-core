<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
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
    public function map(ShoppingListItemTransfer $shoppingListItemTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        $quoteItemTransfer = $this->findItemInQuote($itemTransfer);

        if ($quoteItemTransfer && $this->haveSameProductOptions($quoteItemTransfer, $shoppingListItemTransfer)) {
            $itemTransfer->setGroupKey($quoteItemTransfer->getGroupKey());
        }

        $itemTransfer->setProductOptions($shoppingListItemTransfer->getProductOptions());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer): ?ItemTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();

        return $this->cartClient->findQuoteItem($quoteTransfer, $itemTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return bool
     */
    protected function haveSameProductOptions(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): bool
    {
        if ($itemTransfer->getProductOptions()->count() !== $shoppingListItemTransfer->getProductOptions()->count()) {
            return false;
        }

        $mappingFunction = function (ProductOptionTransfer $productOptionTransfer) {
            /** @var \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer */
            $productOptionValueTransfer = $productOptionTransfer->getValue();
            return $productOptionValueTransfer->getIdProductOptionValue();
        };

        $quoteItemProductOptions = array_map($mappingFunction, $itemTransfer->getProductOptions()->getArrayCopy());
        $shoppingListItemProductOptions = array_map($mappingFunction, $shoppingListItemTransfer->getProductOptions()->getArrayCopy());

        return empty(array_diff($quoteItemProductOptions, $shoppingListItemProductOptions));
    }
}
