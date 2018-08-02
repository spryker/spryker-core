<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;

class ShoppingListCompanyUserMapper implements ShoppingListCompanyUserMapperInterface
{
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer[] $companyUserEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollectionTransfer(
        array $companyUserEntityTransferCollection
    ): ShoppingListCompanyUserCollectionTransfer {
        $shoppingListCompanyUserCollectionTransfer = new ShoppingListCompanyUserCollectionTransfer();

        foreach ($companyUserEntityTransferCollection as $companyUserEntityTransfer) {
            $shoppingListCompanyUserTransfer = $this->mapCompanyUserTransfer(
                $companyUserEntityTransfer,
                new ShoppingListCompanyUserTransfer()
            );

            $virtualPropertiesCollection = $companyUserEntityTransfer->virtualProperties();

            if (isset($virtualPropertiesCollection[static::FIELD_FIRST_NAME]) || isset($virtualPropertiesCollection[static::FIELD_LAST_NAME])) {
                $shoppingListCompanyUserTransfer->setCustomerFullName(
                    $virtualPropertiesCollection[static::FIELD_FIRST_NAME] . ' ' . $virtualPropertiesCollection[static::FIELD_LAST_NAME]
                );
            }

            $shoppingListCompanyUserCollectionTransfer->addCompanyUser($shoppingListCompanyUserTransfer);
        }

        return $shoppingListCompanyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer $companyUserEntityTransfer
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    public function mapCompanyUserTransfer(
        SpyShoppingListCompanyUserEntityTransfer $companyUserEntityTransfer,
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
    ): ShoppingListCompanyUserTransfer {
        $shoppingListCompanyUserTransfer->fromArray($companyUserEntityTransfer->modifiedToArray(), true);

        $shoppingListCompanyUserTransfer
            ->setIdShoppingList($companyUserEntityTransfer->getFkShoppingList())
            ->setIdCompanyUser($companyUserEntityTransfer->getFkCompanyUser())
            ->setIdShoppingListPermissionGroup($companyUserEntityTransfer->getFkShoppingListPermissionGroup());

        return $shoppingListCompanyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser $shoppingListCompanyUserEntity
     *
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser
     */
    public function mapTransferToEntity(
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
