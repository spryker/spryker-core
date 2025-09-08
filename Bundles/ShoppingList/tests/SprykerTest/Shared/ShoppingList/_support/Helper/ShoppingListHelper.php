<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ShoppingList\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShoppingListBuilder;
use Generated\Shared\DataBuilder\ShoppingListItemBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupToPermission;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShoppingListHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function haveShoppingList(array $seed = []): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->buildShoppingList($seed);

        return $this->getLocator()->shoppingList()->facade()->createShoppingList($shoppingListTransfer)->getShoppingList();
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function haveShoppingListFromQuote(int $idQuote, CustomerTransfer $customerTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = (new ShoppingListBuilder())->build();
        $shoppingListTransfer = (new ShoppingListFromCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setCustomer($customerTransfer)
            ->setShoppingListName($shoppingListTransfer->getName());

        return $this->getLocator()->shoppingList()->facade()->createShoppingListFromQuote($shoppingListTransfer);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function buildShoppingList(array $seed = []): ShoppingListTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        $shoppingListTransfer = (new ShoppingListBuilder($seed))->build();

        return $shoppingListTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function buildShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
        $shoppingListItemTransfer = (new ShoppingListItemBuilder($seed))->build();

        return $shoppingListItemTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function haveShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        return $this->getLocator()->shoppingList()->facade()->addShoppingListItem(
            $this->buildShoppingListItem($seed),
        )->getShoppingListItem();
    }

    /**
     * @param string $name
     * @param array $permissionKeys
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function haveShoppingListPermissionGroup(string $name, array $permissionKeys): ShoppingListPermissionGroupTransfer
    {
        $shoppingListPermissionGroupEntity = new SpyShoppingListPermissionGroup();
        $shoppingListPermissionGroupEntity->setName($name);

        foreach ($permissionKeys as $permissionKey) {
            $permissionEntity = SpyPermissionQuery::create()
                ->filterByKey($permissionKey)
                ->findOneOrCreate();

            $quotePermissionGroupToPermissionEntity = new SpyShoppingListPermissionGroupToPermission();
            $quotePermissionGroupToPermissionEntity
                ->setSpyPermission($permissionEntity);

            $shoppingListPermissionGroupEntity->addSpyShoppingListPermissionGroupToPermission($quotePermissionGroupToPermissionEntity);
        }

        $shoppingListPermissionGroupEntity->save();

        $shoppingListPermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $shoppingListPermissionGroupTransfer->fromArray($shoppingListPermissionGroupEntity->toArray(), true);

        return $shoppingListPermissionGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    public function haveShoppingListCompanyUser(
        CompanyUserTransfer $companyUserTransfer,
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
    ): ShoppingListCompanyUserTransfer {
        $shoppingListCompanyUserEntity = new SpyShoppingListCompanyUser();
        $shoppingListCompanyUserEntity->setFkShoppingList($shoppingListTransfer->getIdShoppingListOrFail());
        $shoppingListCompanyUserEntity->setFkCompanyUser($companyUserTransfer->getIdCompanyUserOrFail());
        $shoppingListCompanyUserEntity->setFkShoppingListPermissionGroup($shoppingListPermissionGroupTransfer->getIdShoppingListPermissionGroupOrFail());

        $shoppingListCompanyUserEntity->save();

        return (new ShoppingListCompanyUserTransfer())->fromArray($shoppingListCompanyUserEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    public function haveShoppingListCompanyBusinessUnit(
        CompanyUserTransfer $companyUserTransfer,
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
    ): ShoppingListCompanyBusinessUnitTransfer {
        $shoppingListCompanyBusinessUnitEntity = (new SpyShoppingListCompanyBusinessUnit())
            ->setFkCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnitOrFail())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingListOrFail())
            ->setFkShoppingListPermissionGroup($shoppingListPermissionGroupTransfer->getIdShoppingListPermissionGroupOrFail());

        $shoppingListCompanyBusinessUnitEntity->save();

        return (new ShoppingListCompanyBusinessUnitTransfer())->fromArray($shoppingListCompanyBusinessUnitEntity->toArray(), true);
    }
}
