<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Orm\Zed\SharedCart\Persistence\Map\SpyQuoteCompanyUserTableMap;
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
            $sharedQuoteIdCollection = $this->getSharedQuoteIds($idCompanyUser, $permissionEntity->getIdPermission());

            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->fromArray($permissionEntity->toArray(), true);
            $permissionTransfer->setConfiguration([
                SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION => array_merge($ownQuoteIdCollection, $sharedQuoteIdCollection),
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByCustomer(string $customerReference): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        $ownQuoteIdCollection = $this->getFactory()
            ->createQuoteQuery()
            ->filterByCustomerReference($customerReference)
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->find()
            ->toArray();

        $permissionEntities = $this->getFactory()
            ->createPermissionQuery()
            ->joinSpyQuotePermissionGroupToPermission()
            ->groupByIdPermission()
            ->find();

        foreach ($permissionEntities as $permissionEntity) {
            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->fromArray($permissionEntity->toArray(), true);
            $permissionTransfer->setConfiguration([
                SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION => $ownQuoteIdCollection,
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer[]
     */
    public function findQuotesByIdCompanyUser(int $idCompanyUser): array
    {
        $quoteQuery = $this->getFactory()->createQuoteQuery()
            ->joinWithSpyStore()
            ->useSpyQuoteCompanyUserQuery()
                ->filterByFkCompanyUser($idCompanyUser)
            ->endUse()
            ->addAsColumn('is_default', SpyQuoteCompanyUserTableMap::COL_IS_DEFAULT);
        return $this->buildQueryFromCriteria($quoteQuery)->find();
    }

    /**
     * @param string $customerReference
     *
     * @return array
     */
    public function findShareInformationCustomer($customerReference): array
    {
        $companyUserQuery = $this->getFactory()
            ->createCompanyUserQuery()
            ->useSpyQuoteCompanyUserQuery()
                ->useSpyQuoteQuery()
                    ->filterByCustomerReference($customerReference)
                ->endUse()
            ->endUse()
            ->joinWithCustomer()
            ->joinWithSpyQuoteCompanyUser();
        return $this->buildQueryFromCriteria($companyUserQuery)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    public function findQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): array
    {
        $quotePermissionGroupQuery = $this->getFactory()
            ->createQuotePermissionGroupQuery();
        $modifiedParams = $criteriaFilterTransfer->modifiedToArray();
        if (isset($modifiedParams['is_default'])) {
            $quotePermissionGroupQuery->filterByIsDefault($modifiedParams['is_default']);
        }

        $quotePermissionGroupEntityTransferList = $this->buildQueryFromCriteria($quotePermissionGroupQuery, $criteriaFilterTransfer->getFilter())->find();

        if (!count($quotePermissionGroupEntityTransferList)) {
            return [];
        }

        return $this->mapQuotePermissionGroupList($quotePermissionGroupEntityTransferList);
    }

    /**
     * @param int $idQuote
     *
     * @return array
     */
    public function findQuoteCompanyUserIdCollection(int $idQuote): array
    {
        return $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->select([SpyQuoteCompanyUserTableMap::COL_ID_QUOTE_COMPANY_USER])
            ->find()
            ->toArray();
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

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer[] $quotePermissionGroupEntityTransferList
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    protected function mapQuotePermissionGroupList(array $quotePermissionGroupEntityTransferList): array
    {
        $quotePermissionGroupTransferList = [];
        $mapper = $this->getFactory()->createQuotePermissionGroupMapper();
        foreach ($quotePermissionGroupEntityTransferList as $quotePermissionGroupEntityTransfer) {
            $quotePermissionGroupTransferList[] = $mapper->mapQuotePermissionGroup($quotePermissionGroupEntityTransfer);
        }
        return $quotePermissionGroupTransferList;
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    public function getCustomerIdByReference(string $customerReference): string
    {
        return $this->getFactory()
            ->createSpyCustomerQuery()
            ->filterByCustomerReference($customerReference)
            ->select([SpyCustomerTableMap::COL_ID_CUSTOMER])
            ->findOne()
            ->getIdCustomer();
    }

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isSharedQuoteDefault(int $idQuote, int $idCompanyUser): bool
    {
        return (bool)$this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->filterByFkCompanyUser($idCompanyUser)->count();
    }
}
