<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface
     */
    protected $shoppingListProductOptionReader;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface $shoppingListProductOptionReader
     */
    public function __construct(
        ShoppingListProductOptionReaderInterface $shoppingListProductOptionReader
    ) {
        $this->shoppingListProductOptionReader = $shoppingListProductOptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItemWithProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $productOptionCollectionTransfer = $this->shoppingListProductOptionReader
            ->getShoppingListItemProductOptionsByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        $shoppingListItemTransfer->setProductOptions($productOptionCollectionTransfer->getProductOptions());

        return $shoppingListItemTransfer;
    }
}
