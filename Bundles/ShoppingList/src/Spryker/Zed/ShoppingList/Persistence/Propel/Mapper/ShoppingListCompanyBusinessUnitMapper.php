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

class ShoppingListCompanyBusinessUnitMapper implements ShoppingListCompanyBusinessUnitMapperInterface
{
    /**
     * @param null|\Propel\Runtime\Collection\Collection|null $companyBusinessUnitEntityCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer
     */
    public function mapCompanyBusinessUnitCollectionTransfer(?Collection $companyBusinessUnitEntityCollection): ShoppingListCompanyBusinessUnitCollectionTransfer
    {
        $shoppingListCompanyBusinessUnitCollectionTransfer = new ShoppingListCompanyBusinessUnitCollectionTransfer();

        if (!$companyBusinessUnitEntityCollection) {
            return $shoppingListCompanyBusinessUnitCollectionTransfer;
        }

        foreach ($companyBusinessUnitEntityCollection as $companyBusinessUnitEntity) {
            $shoppingListCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit(
                $this->mapCompanyBusinessUnitTransfer($companyBusinessUnitEntity)
            );
        }

        return $shoppingListCompanyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitTransfer(SpyShoppingListCompanyBusinessUnit $shoppingListCompanyBusinessUnit): ShoppingListCompanyBusinessUnitTransfer
    {
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
    public function mapTransferToEntity(
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
