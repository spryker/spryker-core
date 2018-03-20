<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Propel\Runtime\ActiveQuery\Join;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartPersistenceFactory getFactory()
 */
class SharedCartRepository extends AbstractRepository implements SharedCartRepositoryInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        $ownQuoteIdCollection = $this->findOwnQuotes($idCompanyUser);

        $permissionEntities = $this->getFactory()
            ->createPermissionQuery()
            ->joinSpyQuotePermissionGroupToPermission()
            ->groupByIdPermission()
            ->find();

        foreach ($permissionEntities as $permissionEntity) {
            $haredQuoteIdCollection = $this->getSharedQuoteIds($idCompanyUser, $permissionEntity->getIdPermission());

            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->fromArray($permissionEntity->toArray(), true);
            $permissionTransfer->setConfiguration([
                SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION => $ownQuoteIdCollection + $haredQuoteIdCollection,
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return array
     */
    protected function findOwnQuotes(int $idCompanyUser): array
    {
        $join = new Join(SpyCustomerTableMap::COL_ID_CUSTOMER, SpyCompanyUserTableMap::COL_FK_CUSTOMER);

        return $this->getFactory()
            ->createQuoteQuery()
            ->addJoin(SpyQuoteTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->addJoinObject($join, 'customerJoin')
            ->addJoinCondition('customerJoin', sprintf('%s = %d', SpyCompanyUserTableMap::COL_ID_COMPANY_USER, $idCompanyUser))
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->find()
            ->toArray();
    }

    /**
     * @param int $idCompanyUser
     * @param int $idPermission
     *
     * @return array
     */
    protected function getSharedQuoteIds(int $idCompanyUser, int $idPermission): array
    {
        return $this->getFactory()
            ->createQuoteQuery()
            ->useSpyQuoteCompanyUserQuery()
                ->filterByFkCompanyUser($idCompanyUser)
                ->useSpyQuotePermissionGroupQuery()
                    ->useSpyQuotePermissionGroupToPermissionQuery()
                        ->filterByFkPermission($idPermission)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdQuote()
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->find()
            ->toArray();
    }
}
