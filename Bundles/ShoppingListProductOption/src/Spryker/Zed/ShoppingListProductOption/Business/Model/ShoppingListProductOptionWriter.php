<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business\Model;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface;

class ShoppingListProductOptionWriter implements ShoppingListProductOptionWriterInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface
     */
    protected $shoppingListProductOptionEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface $shoppingListProductOptionEntityManager
     */
    public function __construct(
        ShoppingListProductOptionEntityManagerInterface $shoppingListProductOptionEntityManager
    ) {
        $this->shoppingListProductOptionEntityManager = $shoppingListProductOptionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->shoppingListProductOptionEntityManager
            ->removeShoppingListItemProductOptions($shoppingListItemTransfer->getIdShoppingListItem());

        $this->shoppingListProductOptionEntityManager
            ->saveShoppingListItemProductOptions(
                $shoppingListItemTransfer->getIdShoppingListItem(),
                $shoppingListItemTransfer->getProductOptions()
            );
    }
}
