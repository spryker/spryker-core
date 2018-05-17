<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;

interface ShoppingListMapperInterface
{
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_CREATED_AT = 'created_at';

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListEntityTransfer $shoppingListEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapShoppingListTransfer(
        SpyShoppingListEntityTransfer $shoppingListEntityTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer[] $shoppingListEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function mapCollectionTransfer(array $shoppingListEntityTransferCollection): ShoppingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList $shoppingListEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingList
     */
    public function mapTransferToEntity(
        ShoppingListTransfer $shoppingListTransfer,
        SpyShoppingList $shoppingListEntity
    ): SpyShoppingList;
}
