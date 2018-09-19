<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;
use Propel\Runtime\Collection\Collection;

class ShoppingListCompanyUserMapper implements ShoppingListCompanyUserMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $companyUserEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer $shoppingListCompanyUserCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer
     */
    public function mapCompanyUserEntitiesToShoppingListCompanyUserCollection(
        Collection $companyUserEntityCollection,
        ShoppingListCompanyUserCollectionTransfer $shoppingListCompanyUserCollection
    ): ShoppingListCompanyUserCollectionTransfer {
        foreach ($companyUserEntityCollection as $companyUserEntityTransfer) {
            $shoppingListCompanyUserCollection->addShoppingListCompanyUser(
                $this->mapCompanyUserEntityToCompanyUserTransfer($companyUserEntityTransfer, new ShoppingListCompanyUserTransfer())
            );
        }

        return $shoppingListCompanyUserCollection;
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser $shoppingListCompanyUser
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    public function mapCompanyUserEntityToCompanyUserTransfer(
        SpyShoppingListCompanyUser $shoppingListCompanyUser,
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
    ): ShoppingListCompanyUserTransfer {
        return $shoppingListCompanyUserTransfer
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
