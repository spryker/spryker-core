<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Persistence;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListPersistenceFactory getFactory()
 */
class ProductConfigurationShoppingListEntityManager extends AbstractEntityManager implements ProductConfigurationShoppingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function updateProductConfigurationData(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $shoppingListItemEntity = $this->getFactory()
            ->getShoppingListItemPropelQuery()
            ->filterByUuid($shoppingListItemTransfer->getUuidOrFail())
            ->findOne();

        if (!$shoppingListItemEntity) {
            return;
        }

        $shoppingListItemEntity
            ->setProductConfigurationInstanceData($shoppingListItemTransfer->getProductConfigurationInstanceData())
            ->save();
    }
}
