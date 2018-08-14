<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;
use Propel\Runtime\Collection\Collection;

interface ShoppingListCompanyBusinessUnitMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection|null $companyBusinessUnitEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer[]|\ArrayObject
     */
    public function mapCompanyBusinessUnitEntitiesToCompanyBusinessUnitTransfers(
        ?Collection $companyBusinessUnitEntityTransferCollection
    ): ArrayObject;

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
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
