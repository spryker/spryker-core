<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface;

class ProductOptionValuesRemover implements ProductOptionValuesRemoverInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface
     */
    protected $shoppingListProductOptionConnectorEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionConnectorEntityManager
     */
    public function __construct(ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionConnectorEntityManager)
    {
        $this->shoppingListProductOptionConnectorEntityManager = $shoppingListProductOptionConnectorEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemProductOptionsByRemovedProductOptionValues(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        $productOptionValueIds = $productOptionGroupTransfer->getProductOptionValuesToBeRemoved();
        $productOptionValueIds = array_filter($productOptionValueIds);

        if (!$productOptionValueIds) {
            return;
        }

        $this->shoppingListProductOptionConnectorEntityManager
            ->removeShoppingListItemProductOptionsByProductOptionValueIds($productOptionValueIds);
    }
}
