<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;
use Propel\Runtime\Collection\Collection;

class ShoppingListCompanyUserMapper implements ShoppingListCompanyUserMapperInterface
{
    /**
     * @param null|\Propel\Runtime\Collection\Collection $companyUserEntityCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer[]|\ArrayObject
     */
    public function mapCompanyUserEntitiesToCompanyUserTransfers(?Collection $companyUserEntityCollection): ArrayObject
    {
        $shoppingListCompanyUsers = new ArrayObject();

        if (!$companyUserEntityCollection) {
            return $shoppingListCompanyUsers;
        }

        foreach ($companyUserEntityCollection as $companyUserEntityTransfer) {
            $shoppingListCompanyUsers[] = $this->mapCompanyUserEntityToCompanyUserTransfer($companyUserEntityTransfer);
        }

        return $shoppingListCompanyUsers;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser $shoppingListCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    public function mapCompanyUserEntityToCompanyUserTransfer(SpyShoppingListCompanyUser $shoppingListCompanyUser): ShoppingListCompanyUserTransfer
    {
        return (new ShoppingListCompanyUserTransfer)
            ->setIdShoppingListCompanyUser($shoppingListCompanyUser->getIdShoppingListCompanyUser())
            ->setIdShoppingList($shoppingListCompanyUser->getFkShoppingList())
            ->setIdCompanyUser($shoppingListCompanyUser->getFkCompanyUser())
            ->setIdShoppingListPermissionGroup($shoppingListCompanyUser->getFkShoppingListPermissionGroup());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser $shoppingListCompanyUserEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser
     */
    public function mapCompanyUserTransferToCompanyUserEntity(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer,
        SpyShoppingListCompanyUser $shoppingListCompanyUserEntity
    ): SpyShoppingListCompanyUser {
        $shoppingListCompanyUserEntity->fromArray($shoppingListCompanyUserTransfer->modifiedToArray());

        $shoppingListCompanyUserEntity
            ->setFkCompanyUser($shoppingListCompanyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListCompanyUserTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup());

        return $shoppingListCompanyUserEntity;
    }
}
