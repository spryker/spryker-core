<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface;

class ProductOptionValuesFromShoppingListItemsRemover implements ProductOptionValuesFromShoppingListItemsRemoverInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface $entityManager
     */
    public function __construct(ShoppingListProductOptionConnectorEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function unassignDeletedProductOptionValues(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        $idsProductOptionValue = $productOptionGroupTransfer->getProductOptionValuesToBeRemoved();
        $idsProductOptionValue = array_filter($idsProductOptionValue);

        if (count($idsProductOptionValue) === 0) {
            return;
        }

        $this->entityManager->removeProductOptionValuesFromShoppingListItems($idsProductOptionValue);
    }
}
