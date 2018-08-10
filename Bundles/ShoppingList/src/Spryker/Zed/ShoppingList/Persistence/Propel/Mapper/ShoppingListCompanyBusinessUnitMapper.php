<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;

class ShoppingListCompanyBusinessUnitMapper implements ShoppingListCompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer[] $companyBusinessUnitEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer
     */
    public function mapCompanyBusinessUnitCollectionTransfer(array $companyBusinessUnitEntityTransferCollection): ShoppingListCompanyBusinessUnitCollectionTransfer
    {
        $shoppingListCompanyBusinessUnitCollectionTransfer = new ShoppingListCompanyBusinessUnitCollectionTransfer();
        foreach ($companyBusinessUnitEntityTransferCollection as $companyBusinessUnitEntityTransfer) {
            $shoppingListCompanyBusinessUnitTransfer = $this->mapCompanyBusinessUnitTransfer($companyBusinessUnitEntityTransfer, new ShoppingListCompanyBusinessUnitTransfer());
            $shoppingListCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer);
        }

        return $shoppingListCompanyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer $companyBusinessUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitTransfer(
        SpyShoppingListCompanyBusinessUnitEntityTransfer $companyBusinessUnitEntityTransfer,
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
    ): ShoppingListCompanyBusinessUnitTransfer {
        $shoppingListCompanyBusinessUnitTransfer->fromArray($companyBusinessUnitEntityTransfer->modifiedToArray(), true);

        $shoppingListCompanyBusinessUnitTransfer
            ->setIdShoppingList($companyBusinessUnitEntityTransfer->getFkShoppingList())
            ->setIdCompanyBusinessUnit($companyBusinessUnitEntityTransfer->getFkCompanyBusinessUnit())
            ->setIdShoppingListPermissionGroup($companyBusinessUnitEntityTransfer->getFkShoppingListPermissionGroup());

        return $shoppingListCompanyBusinessUnitTransfer;
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
