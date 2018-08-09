<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Persistence;

use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNoteQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper\ShoppingListItemNoteMapper;
use Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper\ShoppingListItemNoteMapperInterface;

/**
 * @method \Spryker\Zed\ShoppingListNote\ShoppingListNoteConfig getConfig()
 */
class ShoppingListNotePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNoteQuery
     */
    public function createShoppingListItemNoteQuery(): SpyShoppingListItemNoteQuery
    {
        return SpyShoppingListItemNoteQuery::create();
    }

    /**
     * @return \Spryker\Zed\ShoppingListNote\Persistence\Propel\Mapper\ShoppingListItemNoteMapperInterface
     */
    public function createShoppingListItemNoteMapper(): ShoppingListItemNoteMapperInterface
    {
        return new ShoppingListItemNoteMapper();
    }
}
