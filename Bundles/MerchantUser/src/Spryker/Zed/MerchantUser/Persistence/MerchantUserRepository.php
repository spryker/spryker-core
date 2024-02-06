<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Persistence;

use Generated\Shared\Transfer\MerchantUserCollectionTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserPersistenceFactory getFactory()
 */
class MerchantUserRepository extends AbstractRepository implements MerchantUserRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntity = $merchantUserQuery->findOne();

        if (!$merchantUserEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantUserTransfer>
     */
    public function find(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): array
    {
        $merchantUserTransfers = [];
        $merchantUsersQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUsersQuery = $this->applyCriteria($merchantUsersQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntities = $merchantUsersQuery->find();

        foreach ($merchantUserEntities as $merchantUserEntity) {
            $merchantUserTransfers[] = $this->getFactory()->createMerchantUserMapper()
                ->mapMerchantUserEntityToMerchantUserTransfer($merchantUserEntity, new MerchantUserTransfer());
        }

        return $merchantUserTransfers;
    }

    /**
     * @module Merchant
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantUserTransfer>
     */
    public function getMerchantUsers(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): array
    {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery();
        $merchantUserQuery->joinWithSpyMerchant();
        $merchantUserQuery = $this->applyCriteria($merchantUserQuery, $merchantUserCriteriaTransfer);

        $merchantUserEntities = $merchantUserQuery->joinWithSpyMerchant()->find();

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntitiesToMerchantUserTransfers($merchantUserEntities);
    }

    /**
     * @module Merchant
     * @module User
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCollectionTransfer
     */
    public function getMerchantUserCollection(
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): MerchantUserCollectionTransfer {
        $merchantUserQuery = $this->getFactory()->createMerchantUserPropelQuery()
            ->joinWithSpyMerchant()
            ->joinWithSpyUser();

        $merchantUserQuery = $this->applyMerchantUserSearch($merchantUserQuery, $merchantUserCriteriaTransfer);
        $merchantUserQuery = $this->applyMerchantUserSorting($merchantUserQuery, $merchantUserCriteriaTransfer);

        $merchantUserCollectionTransfer = new MerchantUserCollectionTransfer();
        $paginationTransfer = $merchantUserCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $merchantUserQuery = $this->applyPagination($merchantUserQuery, $paginationTransfer);
            $merchantUserCollectionTransfer->setPagination($paginationTransfer);
        }

        $merchantUserEntities = $merchantUserQuery->find();

        return $this->getFactory()
            ->createMerchantUserMapper()
            ->mapMerchantUserEntitiesToMerchantUserCollectionTransfer(
                $merchantUserEntities,
                $merchantUserCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser> $merchantUserQuery
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser>
     */
    protected function applyCriteria(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): SpyMerchantUserQuery {
        if ($merchantUserCriteriaTransfer->getIdUser() !== null) {
            $merchantUserQuery->filterByFkUser($merchantUserCriteriaTransfer->getIdUser());
        }

        if ($merchantUserCriteriaTransfer->getIdMerchant() !== null) {
            $merchantUserQuery->filterByFkMerchant($merchantUserCriteriaTransfer->getIdMerchant());
        }

        if ($merchantUserCriteriaTransfer->getIdMerchantUser() !== null) {
            $merchantUserQuery->filterByIdMerchantUser($merchantUserCriteriaTransfer->getIdMerchantUser());
        }

        if ($merchantUserCriteriaTransfer->getUsername()) {
            $merchantUserQuery->useSpyUserQuery()
                    ->filterByUsername($merchantUserCriteriaTransfer->getUsername())
                ->endUse();
        }

        if ($merchantUserCriteriaTransfer->getStatus()) {
            $merchantUserQuery->useSpyUserQuery()
                    ->filterByStatus($merchantUserCriteriaTransfer->getStatus())
                ->endUse();
        }

        if ($merchantUserCriteriaTransfer->getMerchantStatus()) {
            $merchantUserQuery->useSpyMerchantQuery()
                    ->filterByStatus($merchantUserCriteriaTransfer->getMerchantStatus())
                ->endUse();
        }

        $merchantUserQuery->orderByIdMerchantUser();

        return $merchantUserQuery;
    }

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser> $merchantUserQuery
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery<\Orm\Zed\MerchantUser\Persistence\SpyMerchantUser>
     */
    protected function applyMerchantUserSearch(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): SpyMerchantUserQuery {
        $merchantUserSearchConditionsTransfer = $merchantUserCriteriaTransfer->getMerchantUserSearchConditions();

        if (!$merchantUserSearchConditionsTransfer) {
            return $merchantUserQuery;
        }

        if ($merchantUserSearchConditionsTransfer->getMerchantName() !== null) {
            $merchantUserQuery->_or()
                ->useSpyMerchantQuery()
                    ->filterByName_Like(sprintf('%%%s%%', $merchantUserSearchConditionsTransfer->getMerchantName()))
                ->endUse();
        }

        if ($merchantUserSearchConditionsTransfer->getUserFirstName() !== null) {
            $merchantUserQuery->_or()
                ->useSpyUserQuery()
                    ->filterByFirstName_Like(sprintf('%%%s%%', $merchantUserSearchConditionsTransfer->getUserFirstName()))
                ->endUse();
        }

        if ($merchantUserSearchConditionsTransfer->getUserLastName() !== null) {
            $merchantUserQuery->_or()
                ->useSpyUserQuery()
                    ->filterByLastName_Like(sprintf('%%%s%%', $merchantUserSearchConditionsTransfer->getUserLastName()))
                ->endUse();
        }

        if ($merchantUserSearchConditionsTransfer->getUsername() !== null) {
            $merchantUserQuery->_or()
                ->useSpyUserQuery()
                    ->filterByUsername_Like(sprintf('%%%s%%', $merchantUserSearchConditionsTransfer->getUsername()))
                ->endUse();
        }

        return $merchantUserQuery;
    }

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery $merchantUserQuery
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    protected function applyMerchantUserSorting(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
    ): SpyMerchantUserQuery {
        $sortTransfers = $merchantUserCriteriaTransfer->getSortCollection();
        foreach ($sortTransfers as $sortTransfer) {
            $merchantUserQuery->orderBy($sortTransfer->getFieldOrFail(), $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $merchantUserQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }
}
