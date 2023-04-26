<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnit;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitBlacklist;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyBusinessUnitBlacklistQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUser;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListCompanyUserQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShoppingListBusinessTester extends Actor
{
    use _generated\ShoppingListBusinessTesterActions;

    /**
     * @var string
     */
    protected const SHOPPING_LIST_PERMISSION_GROUP_NAME = 'SHOPPING_LIST_PERMISSION_GROUP_NAME';

    /**
     * @var list<string>
     */
    protected const SHOPPING_LIST_PERMISSION_GROUP_KEYS = [
        'SHOPPING_LIST_PERMISSION_GROUP_KEY_1',
        'SHOPPING_LIST_PERMISSION_GROUP_KEY_2',
    ];

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer
     */
    protected $company;

    /**
     * @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected $companyBusinessUnit;

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function createCompany(): CompanyTransfer
    {
        return $this->haveCompany(
            [
                CompanyTransfer::NAME => 'Test company',
                CompanyTransfer::STATUS => 'approved',
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::INITIAL_USER_TRANSFER => new CompanyUserTransfer(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer): CompanyBusinessUnitTransfer
    {
        return $this->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingList(CompanyUserTransfer $companyUserTransfer): ShoppingListTransfer
    {
        return $this->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer->getCustomer()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);
    }

    /**
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductConcrete(bool $isActive): ProductConcreteTransfer
    {
        $productConcreteOverride = [
            ProductConcreteTransfer::IS_ACTIVE => $isActive,
        ];

        return $this->haveFullProduct($productConcreteOverride);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function createShoppingListItem(?ProductConcreteTransfer $productConcreteTransfer = null): ShoppingListItemTransfer
    {
        $productConcreteTransfer = $productConcreteTransfer ?? $this->haveProduct();
        $customerTransfer = $this->haveCustomer();

        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::IS_ACTIVE => true,
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $shoppingListTransfer = $this->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);

        return $this->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUserForBusinessUnit(): CompanyUserTransfer
    {
        $customerTransfer = $this->haveCustomer();
        if (!$this->company) {
            $this->company = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        }
        if (!$this->companyBusinessUnit) {
            $this->companyBusinessUnit = $this->haveCompanyBusinessUnit([
                CompanyBusinessUnitTransfer::FK_COMPANY => $this->company->getIdCompany(),
            ]);
        }

        return $this->haveCompanyUser([
            CompanyUserTransfer::IS_ACTIVE => true,
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->company->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->companyBusinessUnit->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    public function shareShopppingListWithCompanyUser(
        ShoppingListTransfer $shoppingListTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): ShoppingListCompanyUserTransfer {
        $shoppingListPermissionGroupTransfer = $this->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS, [
            ReadShoppingListPermissionPlugin::KEY,
            WriteShoppingListPermissionPlugin::KEY,
        ]);

        $shoppingListCompanyUserEntity = (new SpyShoppingListCompanyUser())
            ->setFkCompanyUser($companyUserTransfer->getIdCompanyUserOrFail())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingListOrFail())
            ->setFkShoppingListPermissionGroup($shoppingListPermissionGroupTransfer->getIdShoppingListPermissionGroupOrFail());

        $shoppingListCompanyUserEntity->save();

        return (new ShoppingListCompanyUserTransfer())->fromArray($shoppingListCompanyUserEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer
     */
    public function createShoppingListCompanyBusinessUnitBlacklist(
        CompanyUserTransfer $companyUserTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): ShoppingListCompanyBusinessUnitBlacklistTransfer {
        $shoppingListPermissionGroupTransfer = $this->haveShoppingListPermissionGroup(
            static::SHOPPING_LIST_PERMISSION_GROUP_NAME,
            static::SHOPPING_LIST_PERMISSION_GROUP_KEYS,
        );

        $shoppingListCompanyBusinessUnitEntity = (new SpyShoppingListCompanyBusinessUnit())
            ->setFkCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnitOrFail())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingListOrFail())
            ->setFkShoppingListPermissionGroup($shoppingListPermissionGroupTransfer->getIdShoppingListPermissionGroupOrFail());

        $shoppingListCompanyBusinessUnitBlacklistEntity = (new SpyShoppingListCompanyBusinessUnitBlacklist())
            ->setSpyShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitEntity)
            ->setFkCompanyUser($companyUserTransfer->getIdCompanyUserOrFail());

        $shoppingListCompanyBusinessUnitBlacklistEntity->save();

        return (new ShoppingListCompanyBusinessUnitBlacklistTransfer())
            ->fromArray($shoppingListCompanyBusinessUnitBlacklistEntity->toArray());
    }

    /**
     * @param list<int> $shoppingListCompanyBusinessUnitBlacklistIds
     *
     * @return list<\Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer>
     */
    public function findShoppingListCompanyBusinessUnitBlacklists(array $shoppingListCompanyBusinessUnitBlacklistIds): array
    {
        $shoppingListCompanyBusinessUnitBlacklistEntities = (new SpyShoppingListCompanyBusinessUnitBlacklistQuery())
            ->filterByIdShoppingListCompanyBusinessUnitBlacklist_In($shoppingListCompanyBusinessUnitBlacklistIds)
            ->find();

        $shoppingListCompanyBusinessUnitBlacklistTransfers = [];

        foreach ($shoppingListCompanyBusinessUnitBlacklistEntities as $shoppingListCompanyBusinessUnitBlacklistEntity) {
            $shoppingListCompanyBusinessUnitBlacklistTransfers[] =
               (new ShoppingListCompanyBusinessUnitBlacklistTransfer())->fromArray(
                   $shoppingListCompanyBusinessUnitBlacklistEntity->toArray(),
               );
        }

        return $shoppingListCompanyBusinessUnitBlacklistTransfers;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserCollectionTransfer
     */
    public function findShoppingListCompanyUsers(int $idCompanyUser): ShoppingListCompanyUserCollectionTransfer
    {
        $shoppingListCompanyUserEntities = (new SpyShoppingListCompanyUserQuery())
            ->filterByFkCompanyUser($idCompanyUser)
            ->find();
        $shoppingListCompanyUserCollectionTransfer = new ShoppingListCompanyUserCollectionTransfer();

        foreach ($shoppingListCompanyUserEntities as $shoppingListCompanyUserEntity) {
            $shoppingListCompanyUserCollectionTransfer->addShoppingListCompanyUser(
                (new ShoppingListCompanyUserTransfer())->fromArray(
                    $shoppingListCompanyUserEntity->toArray(),
                ),
            );
        }

        return $shoppingListCompanyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findShoppingList(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListEntity = (new SpyShoppingListQuery())
            ->filterByIdShoppingList($shoppingListTransfer->getIdShoppingListOrFail())
            ->filterByCustomerReference($shoppingListTransfer->getCustomerReference())
            ->findOne();

        if (!$shoppingListEntity) {
            return null;
        }

        return (new ShoppingListTransfer())->fromArray($shoppingListEntity->toArray());
    }
}
