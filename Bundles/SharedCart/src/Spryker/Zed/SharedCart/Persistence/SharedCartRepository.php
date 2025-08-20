<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Orm\Zed\SharedCart\Persistence\Map\SpyQuoteCompanyUserTableMap;
use Orm\Zed\SharedCart\Persistence\Map\SpyQuotePermissionGroupToPermissionTableMap;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
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

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Permission\Persistence\SpyPermission> $permissionEntities */
        $permissionEntities = $this->getFactory()
            ->createPermissionQuery()
            ->joinSpyQuotePermissionGroupToPermission()
            ->groupByIdPermission()
            ->find();

        $permissionIds = $this->mapPermissionIds($permissionEntities);

        $quoteCompanyUserCollection = $this->getCompanyUserQuotesWithPermissions($idCompanyUser, $permissionIds);
        $quoteIdsGroupedByIdPermission = $this->groupQuoteIdsByIdPermission($quoteCompanyUserCollection);

        foreach ($permissionEntities as $permissionEntity) {
            $sharedQuoteIdCollection = $quoteIdsGroupedByIdPermission[$permissionEntity->getIdPermission()] ?? [];

            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->fromArray($permissionEntity->toArray(), true);
            $permissionTransfer->setConfiguration([
                SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION => array_unique(array_merge($ownQuoteIdCollection, $sharedQuoteIdCollection)),
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Permission\Persistence\SpyPermission> $permissionEntities
     *
     * @return array<int>
     */
    protected function mapPermissionIds(ObjectCollection $permissionEntities): array
    {
        $permissionIds = [];
        foreach ($permissionEntities as $permissionEntity) {
            $permissionIds[] = $permissionEntity->getIdPermission();
        }

        return $permissionIds;
    }

    /**
     * @param array<array<int>> $quoteIdsByPermissions
     *
     * @return array<array<int>>
     */
    protected function groupQuoteIdsByIdPermission(array $quoteIdsByPermissions): array
    {
        $groupedQuoteIds = [];
        foreach ($quoteIdsByPermissions as $quoteByPermission) {
            $quoteId = $quoteByPermission[SpyQuoteTableMap::COL_ID_QUOTE];
            $permissionId = $quoteByPermission[SpyQuotePermissionGroupToPermissionTableMap::COL_FK_PERMISSION];

            if (!isset($groupedQuoteIds[$permissionId])) {
                $groupedQuoteIds[$permissionId] = [];
            }
            $groupedQuoteIds[$permissionId][] = $quoteId;
        }

        return $groupedQuoteIds;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByCustomer(string $customerReference): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        /** @var \Propel\Runtime\Collection\ArrayCollection $quoteIds */
        $quoteIds = $this->getFactory()
            ->createQuoteQuery()
            ->filterByCustomerReference($customerReference)
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->find();

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Permission\Persistence\SpyPermission> $permissionEntities */
        $permissionEntities = $this->getFactory()
            ->createPermissionQuery()
            ->joinSpyQuotePermissionGroupToPermission()
            ->groupByIdPermission()
            ->find();

        foreach ($permissionEntities as $permissionEntity) {
            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->fromArray($permissionEntity->toArray(), true);
            $permissionTransfer->setConfiguration([
                SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION => $quoteIds->toArray(),
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @module Quote
     *
     * @param \Generated\Shared\Transfer\SharedQuoteCriteriaFilterTransfer $sharedQuoteCriteriaFilterTransfer
     *
     * @return array<int>
     */
    public function getIsDefaultFlagForSharedCartsBySharedQuoteCriteriaFilter(SharedQuoteCriteriaFilterTransfer $sharedQuoteCriteriaFilterTransfer): array
    {
        $sharedQuoteCriteriaFilterTransfer->requireIdCompanyUser();

        $quoteQuery = $this->getFactory()->createQuoteCompanyUserQuery()
            ->filterByFkCompanyUser($sharedQuoteCriteriaFilterTransfer->getIdCompanyUser())
            ->useSpyQuoteQuery()
                ->filterByFkStore($sharedQuoteCriteriaFilterTransfer->getIdStore())
            ->endUse()
            ->select([
                SpyQuoteCompanyUserTableMap::COL_FK_QUOTE,
                SpyQuoteCompanyUserTableMap::COL_IS_DEFAULT,
            ]);

        /** @var \Propel\Runtime\Collection\ObjectCollection $quotes */
        $quotes = $quoteQuery->find();

        return $quotes->toKeyValue(SpyQuoteCompanyUserTableMap::COL_FK_QUOTE, SpyQuoteCompanyUserTableMap::COL_IS_DEFAULT);
    }

    /**
     * @param string $customerReference
     *
     * @return array<\Generated\Shared\Transfer\SpyCompanyUserEntityTransfer>
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
     * @return array<\Generated\Shared\Transfer\QuotePermissionGroupTransfer>
     */
    public function findQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): array
    {
        $quotePermissionGroupQuery = $this->getFactory()
            ->createQuotePermissionGroupQuery();
        $modifiedParams = $criteriaFilterTransfer->modifiedToArray(true, true);

        if (isset($modifiedParams[QuotePermissionGroupCriteriaFilterTransfer::IS_DEFAULT])) {
            $quotePermissionGroupQuery->filterByIsDefault($modifiedParams[QuotePermissionGroupCriteriaFilterTransfer::IS_DEFAULT]);
        }

        if (isset($modifiedParams[QuotePermissionGroupCriteriaFilterTransfer::NAME])) {
            $quotePermissionGroupQuery->filterByName($modifiedParams[QuotePermissionGroupCriteriaFilterTransfer::NAME]);
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
     * @return array<int>
     */
    public function findQuoteCompanyUserIdCollection(int $idQuote): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $quoteCompanyUserIds */
        $quoteCompanyUserIds = $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->select([SpyQuoteCompanyUserTableMap::COL_ID_QUOTE_COMPANY_USER])
            ->find();

        return $quoteCompanyUserIds->toArray();
    }

    /**
     * @param int $idQuote
     *
     * @return array<int>
     */
    public function findAllCompanyUserQuotePermissionGroupIdIndexes(int $idQuote): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $quoteCompanyUserPermissions */
        $quoteCompanyUserPermissions = $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->select([
                SpyQuoteCompanyUserTableMap::COL_ID_QUOTE_COMPANY_USER,
                SpyQuoteCompanyUserTableMap::COL_FK_QUOTE_PERMISSION_GROUP,
            ])
            ->find();

        $mappedQuotePermissionGroupIdIndexes = $this->mapStoredQuotePermissionGroupIdIndexesToAssociativeArray(
            $quoteCompanyUserPermissions->toArray(),
        );

        return $mappedQuotePermissionGroupIdIndexes;
    }

    /**
     * @param array $storedQuotePermissionGroupIdIndexes
     *
     * @return array<int>
     */
    protected function mapStoredQuotePermissionGroupIdIndexesToAssociativeArray(array $storedQuotePermissionGroupIdIndexes): array
    {
        $mappedQuotePermissionGroupIdIndexes = [];
        foreach ($storedQuotePermissionGroupIdIndexes as $storedQuotePermissionGroupIdIndex) {
            $idQuoteCompanyUser = $storedQuotePermissionGroupIdIndex[SpyQuoteCompanyUserTableMap::COL_ID_QUOTE_COMPANY_USER];
            $mappedQuotePermissionGroupIdIndexes[$idQuoteCompanyUser] = $storedQuotePermissionGroupIdIndex[SpyQuoteCompanyUserTableMap::COL_FK_QUOTE_PERMISSION_GROUP];
        }

        return $mappedQuotePermissionGroupIdIndexes;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return array<int>
     */
    protected function findOwnQuotes(int $idCompanyUser): array
    {
        $join = new Join(SpyCustomerTableMap::COL_ID_CUSTOMER, SpyCompanyUserTableMap::COL_FK_CUSTOMER);

        /** @var \Propel\Runtime\Collection\ArrayCollection $quoteIds */
        $quoteIds = $this->getFactory()
            ->createQuoteQuery()
            ->addJoin(SpyQuoteTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->addJoinObject($join, 'customerJoin')
            ->addJoinCondition('customerJoin', sprintf('%s = %d', SpyCompanyUserTableMap::COL_ID_COMPANY_USER, $idCompanyUser))
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->find();

        $ownQuoteIdCollection = array_map(function ($value) {
            return (int)$value;
        }, $quoteIds->toArray());

        return $ownQuoteIdCollection;
    }

    /**
     * @param int $idCompanyUser
     * @param array<int> $idPermissions
     *
     * @return array<array<int>>
     */
    protected function getCompanyUserQuotesWithPermissions(int $idCompanyUser, array $idPermissions): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $companyUserQuotesWithPermissions */
        $companyUserQuotesWithPermissions = $this->getFactory()
            ->createQuoteQuery()
            ->useSpyQuoteCompanyUserQuery()
                ->filterByFkCompanyUser($idCompanyUser)
                ->useSpyQuotePermissionGroupQuery()
                    ->useSpyQuotePermissionGroupToPermissionQuery()
                        ->filterByFkPermission_In($idPermissions)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select([SpyQuoteTableMap::COL_ID_QUOTE, SpyQuotePermissionGroupToPermissionTableMap::COL_FK_PERMISSION])
            ->find();

        return $companyUserQuotesWithPermissions->toArray();
    }

    /**
     * @param int $idCompanyUser
     * @param int $idPermission
     *
     * @return array<int>
     */
    protected function getSharedQuoteIds(int $idCompanyUser, int $idPermission): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $sharedQuoteIds */
        $sharedQuoteIds = $this->getFactory()
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
            ->find();

        return $sharedQuoteIds->toArray();
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer> $quotePermissionGroupEntityTransferList
     *
     * @return array<\Generated\Shared\Transfer\QuotePermissionGroupTransfer>
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
        return (string)$this->getFactory()
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
        return $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->filterByFkCompanyUser($idCompanyUser)
            ->filterByIsDefault(true)
            ->exists();
    }

    /**
     * @param array<int> $quoteIds
     * @param bool $excludeAnonymizedCustomers
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getQuoteCompanyUserEntities(array $quoteIds, bool $excludeAnonymizedCustomers = false): Collection
    {
        $quoteCompanyUserQuery = $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote_In($quoteIds)
            ->joinWithSpyCompanyUser()
            ->useSpyCompanyUserQuery(null, Criteria::LEFT_JOIN)
                ->joinWithCustomer()
            ->endUse();

        if ($excludeAnonymizedCustomers) {
            $quoteCompanyUserQuery
                ->addAnd(
                    SpyCustomerTableMap::COL_ANONYMIZED_AT,
                    null,
                    Criteria::ISNULL,
                );
        }

        return $quoteCompanyUserQuery->find();
    }

    /**
     * @param int $idQuote
     * @param bool $excludeAnonymizedCustomers
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function findShareDetailsByQuoteId(int $idQuote, bool $excludeAnonymizedCustomers = false): ShareDetailCollectionTransfer
    {
        $quoteCompanyUserEntities = $this->getQuoteCompanyUserEntities([$idQuote], $excludeAnonymizedCustomers);

        return $this->getFactory()
            ->createQuoteShareDetailMapper()
            ->mapShareDetailCollection($quoteCompanyUserEntities, $this->findQuotePermissionGroupList(new QuotePermissionGroupCriteriaFilterTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     * @param bool $excludeAnonymizedCustomers
     *
     * @return array<\Generated\Shared\Transfer\ShareDetailCollectionTransfer>
     */
    public function getSharedCartDetails(ShareCartRequestTransfer $shareCartRequestTransfer, bool $excludeAnonymizedCustomers = false): array
    {
        $quoteIds = $shareCartRequestTransfer->getQuoteIds();
        $quoteCompanyUserEntities = $this->getQuoteCompanyUserEntities($quoteIds, $excludeAnonymizedCustomers);
        $permissionGroupList = $this->findQuotePermissionGroupList(new QuotePermissionGroupCriteriaFilterTransfer());

        return $this->getFactory()
            ->createQuoteShareDetailMapper()
            ->mapShareDetailCollectionByQuoteId($quoteCompanyUserEntities, $permissionGroupList);
    }

    /**
     * @param int $idQuotePermissionGroup
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    public function findQuotePermissionGroupById(int $idQuotePermissionGroup): ?QuotePermissionGroupTransfer
    {
        $quotePermissionGroupEntity = $this->getFactory()
            ->createQuotePermissionGroupQuery()
            ->findOneByIdQuotePermissionGroup($idQuotePermissionGroup);

        if (!$quotePermissionGroupEntity) {
            return null;
        }

        return $this->getFactory()
            ->createQuotePermissionGroupMapper()
            ->mapQuotePermissionGroupEntityToQuotePermissionGroupTransfer(
                $quotePermissionGroupEntity,
                new QuotePermissionGroupTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailCollectionByShareDetailCriteria(
        ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
    ): ShareDetailCollectionTransfer {
        $quoteCompanyUserQuery = $this->getFactory()->createQuoteCompanyUserQuery();
        $quoteCompanyUserQuery = $this->applySharedDetailCriteriaFiltersToQuoteCompanyUserQuery(
            $quoteCompanyUserQuery,
            $shareDetailCriteriaFilterTransfer,
        );

        $quoteCompanyUserQuery
            ->joinWithSpyCompanyUser()
            ->useSpyCompanyUserQuery(null, Criteria::LEFT_JOIN)
            ->joinWithCustomer()
            ->endUse();

        $quoteCompanyUserEntities = $quoteCompanyUserQuery->find();

        return $this->getFactory()
            ->createQuoteShareDetailMapper()
            ->mapShareDetailCollection($quoteCompanyUserEntities, $this->findQuotePermissionGroupList(new QuotePermissionGroupCriteriaFilterTransfer()));
    }

    /**
     * @param string $quoteCompanyUserUuid
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer|null
     */
    public function findQuoteCompanyUserByUuid(string $quoteCompanyUserUuid): ?QuoteCompanyUserTransfer
    {
        $quoteCompanyUser = $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->joinWithSpyQuote()
            ->filterByUuid($quoteCompanyUserUuid)
            ->findOne();

        if (!$quoteCompanyUser) {
            return null;
        }

        return $this->getFactory()
            ->createQuoteCompanyUserMapper()
            ->mapQuoteCompanyUserEntityToQuoteCompanyUserTransfer($quoteCompanyUser, new QuoteCompanyUserTransfer());
    }

    /**
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery $quoteCompanyUserQuery
     * @param \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
     *
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery
     */
    protected function applySharedDetailCriteriaFiltersToQuoteCompanyUserQuery(
        SpyQuoteCompanyUserQuery $quoteCompanyUserQuery,
        ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
    ): SpyQuoteCompanyUserQuery {
        if ($shareDetailCriteriaFilterTransfer->getIdQuoteCompanyUser()) {
            $quoteCompanyUserQuery->filterByIdQuoteCompanyUser($shareDetailCriteriaFilterTransfer->getIdQuoteCompanyUser());
        }
        if ($shareDetailCriteriaFilterTransfer->getIdQuote()) {
            $quoteCompanyUserQuery->filterByFkQuote($shareDetailCriteriaFilterTransfer->getIdQuote());
        }
        if ($shareDetailCriteriaFilterTransfer->getIdCompanyUser()) {
            $quoteCompanyUserQuery->filterByFkCompanyUser($shareDetailCriteriaFilterTransfer->getIdCompanyUser());
        }

        return $quoteCompanyUserQuery;
    }

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer|null
     */
    public function findShareDetailByIdQuoteAndIdCompanyUser(int $idQuote, int $idCompanyUser): ?ShareDetailTransfer
    {
        $quoteCompanyUserEntity = $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOne();

        if (!$quoteCompanyUserEntity) {
            return null;
        }

        return $this->getFactory()
            ->createQuoteShareDetailMapper()
            ->mapQuoteCompanyUserToShareDetailTransfer($quoteCompanyUserEntity);
    }

    /**
     * @module Customer
     * @module CompanyUser
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollectionByQuote(int $idQuote): CustomerCollectionTransfer
    {
        $customerEntityCollection = $this->getFactory()->createSpyCustomerQuery()
            ->joinWithCompanyUser()
            ->useCompanyUserQuery()
                ->joinWithSpyQuoteCompanyUser()
                ->useSpyQuoteCompanyUserQuery()
                    ->filterByFkQuote($idQuote)
                ->endUse()
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createCustomerMapper()
            ->mapCustomerEntityCollectionToCustomerTransferCollection(
                $customerEntityCollection,
                new CustomerCollectionTransfer(),
            );
    }
}
