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

class ShoppingListCompanyBusinessUnitMapper implements ShoppingListCompanyBusinessUnitMapperInterface
{
    /**
     * @param null|\Propel\Runtime\Collection\Collection $companyBusinessUnitEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer[]|\ArrayObject
     */
    public function mapCompanyBusinessUnitEntitiesToCompanyBusinessUnitTransfers(
        ?Collection $companyBusinessUnitEntityTransferCollection
    ): ArrayObject {
        $shoppingListCompanyBusinessUnits = new ArrayObject();

        if (!$companyBusinessUnitEntityTransferCollection) {
            return $shoppingListCompanyBusinessUnits;
        }

        foreach ($companyBusinessUnitEntityTransferCollection as $companyBusinessUnitEntity) {
            $shoppingListCompanyBusinessUnits[] = $this->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer($companyBusinessUnitEntity);
        }

        return $shoppingListCompanyBusinessUnits;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
    ): ShoppingListCompanyBusinessUnitTransfer {
        return (new ShoppingListCompanyBusinessUnitTransfer)
            ->setIdShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnit->getIdShoppingListCompanyBusinessUnit())
            ->setIdShoppingList($shoppingListCompanyBusinessUnit->getFkShoppingList())
            ->setIdCompanyBusinessUnit($shoppingListCompanyBusinessUnit->getFkCompanyBusinessUnit())
            ->setIdShoppingListPermissionGroup($shoppingListCompanyBusinessUnit->getFkShoppingListPermissionGroup());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit
     */
    public function mapCompanyBusinessUnitTransferToCompanyBusinessUnitEntity(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer,
        SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnitEntity
    ): SpyShoppingListCompanyBusinessUnit {
        $shoppingListCompanyBusinessUnitEntity->fromArray($shoppingListCompanyBusinessUnitTransfer->modifiedToArray());

        $shoppingListCompanyBusinessUnitEntity
            ->setFkCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setFkShoppingList($shoppingListCompanyBusinessUnitTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup());

        return $shoppingListCompanyBusinessUnitEntity;
    }
}
