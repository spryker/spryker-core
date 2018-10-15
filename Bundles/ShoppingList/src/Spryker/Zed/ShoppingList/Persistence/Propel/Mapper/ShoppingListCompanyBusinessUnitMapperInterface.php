<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;
use Propel\Runtime\Collection\Collection;

interface ShoppingListCompanyBusinessUnitMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $companyBusinessUnitEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer
     */
    public function mapCompanyBusinessUnitEntitiesToShoppingListCompanyBusinessUnitCollection(
        Collection $companyBusinessUnitEntityCollection,
        ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollection
    ): ShoppingListCompanyBusinessUnitCollectionTransfer;

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit,
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
    ): ShoppingListCompanyBusinessUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit
     */
    public function mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer,
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
    ): SpyShoppingListCompanyBusinessUnit;
}
