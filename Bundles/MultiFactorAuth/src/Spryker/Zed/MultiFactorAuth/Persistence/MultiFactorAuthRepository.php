<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthCodesTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyUserMultiFactorAuthCodesTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyUserMultiFactorAuthTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthPersistenceFactory getFactory()
 */
class MultiFactorAuthRepository extends AbstractRepository implements MultiFactorAuthRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function getCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer
    {
        $customerMultiFactorAuthCodeEntity = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodeQuery()
            ->innerJoinSpyCustomerMultiFactorAuth()
            ->useSpyCustomerMultiFactorAuthQuery();

        if ($multiFactorAuthTransfer->getType() !== null) {
            $customerMultiFactorAuthCodeEntity = $customerMultiFactorAuthCodeEntity->filterByType($multiFactorAuthTransfer->getType());
        }

        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes|null $customerMultiFactorAuthCodeEntity */
        $customerMultiFactorAuthCodeEntity = $customerMultiFactorAuthCodeEntity->filterByFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomer())
            ->addDescendingOrderByColumn(SpyCustomerMultiFactorAuthCodesTableMap::COL_ID_CUSTOMER_MULTI_FACTOR_AUTH_CODE)
            ->endUse()
            ->findOne();

        if ($customerMultiFactorAuthCodeEntity === null) {
            return new MultiFactorAuthCodeTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapCustomerMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer($customerMultiFactorAuthCodeEntity, new MultiFactorAuthCodeTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function getUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer
    {
        $userMultiFactorAuthCodeEntity = $this->getFactory()
            ->createSpyUserMultiFactorAuthCodeQuery()
            ->innerJoinSpyUserMultiFactorAuth()
            ->useSpyUserMultiFactorAuthQuery();

        if ($multiFactorAuthTransfer->getType() !== null) {
            $userMultiFactorAuthCodeEntity = $userMultiFactorAuthCodeEntity->filterByType($multiFactorAuthTransfer->getType());
        }

        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuthCodes|null $userMultiFactorAuthCodeEntity */
        $userMultiFactorAuthCodeEntity = $userMultiFactorAuthCodeEntity->filterByFkUser($multiFactorAuthTransfer->getUserOrFail()->getIdUser())
            ->addDescendingOrderByColumn(SpyUserMultiFactorAuthCodesTableMap::COL_ID_USER_MULTI_FACTOR_AUTH_CODE)
            ->endUse()
            ->findOne();

        if ($userMultiFactorAuthCodeEntity === null) {
            return new MultiFactorAuthCodeTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapUserMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer($userMultiFactorAuthCodeEntity, new MultiFactorAuthCodeTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findCustomerMultiFactorAuthCodeByCriteria(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer {
        $customerMultiFactorAuthCodeQuery = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodeQuery()
            ->innerJoinSpyCustomerMultiFactorAuth();

        if ($multiFactorAuthCodeCriteriaTransfer->getStatus() !== null) {
            $customerMultiFactorAuthCodeQuery->filterByStatus($multiFactorAuthCodeCriteriaTransfer->getStatus());
        }
        if ($multiFactorAuthCodeCriteriaTransfer->getCode() !== null) {
            $customerMultiFactorAuthCodeQuery->filterByCode($multiFactorAuthCodeCriteriaTransfer->getCode());
        }

        $customerMultiFactorAuthCodeQuery = $customerMultiFactorAuthCodeQuery
            ->useSpyCustomerMultiFactorAuthQuery()
                ->filterByFkCustomer($multiFactorAuthCodeCriteriaTransfer->getCustomerOrFail()->getIdCustomer());

        if ($multiFactorAuthCodeCriteriaTransfer->getType() !== null) {
            $customerMultiFactorAuthCodeQuery->filterByType($multiFactorAuthCodeCriteriaTransfer->getType());
        }

        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes|null $customerMultiFactorAuthCodeEntity */
        $customerMultiFactorAuthCodeEntity = $customerMultiFactorAuthCodeQuery->addDescendingOrderByColumn(SpyCustomerMultiFactorAuthCodesTableMap::COL_ID_CUSTOMER_MULTI_FACTOR_AUTH_CODE)
            ->endUse()
            ->findOne();

        if ($customerMultiFactorAuthCodeEntity === null) {
            return new MultiFactorAuthCodeTransfer();
        }

        $multiFactorAuthCodeTransfer = $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapCustomerMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer($customerMultiFactorAuthCodeEntity, new MultiFactorAuthCodeTransfer());

        return $multiFactorAuthCodeTransfer->setType($customerMultiFactorAuthCodeEntity->getSpyCustomerMultiFactorAuth()->getType());
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        $customerMultiFactorAuthQuery = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($multiFactorAuthCriteriaTransfer->getCustomerOrFail()->getIdCustomer());

        $customerMultiFactorAuthQuery = $this->addStatusFilter($customerMultiFactorAuthQuery, $multiFactorAuthCriteriaTransfer);
        $customerMultiFactorAuthEntities = $customerMultiFactorAuthQuery->find();

        if ($customerMultiFactorAuthEntities->count() === 0) {
            return new MultiFactorAuthTypesCollectionTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer($customerMultiFactorAuthEntities, new MultiFactorAuthTypesCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getUserMultiFactorAuthTypes(MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        $userMultiFactorAuthQuery = $this->getFactory()
            ->createSpyUserMultiFactorAuthQuery()
            ->filterByFkUser($multiFactorAuthCriteriaTransfer->getUserOrFail()->getIdUser());

        $userMultiFactorAuthQuery = $this->addStatusFilter($userMultiFactorAuthQuery, $multiFactorAuthCriteriaTransfer);
        $userMultiFactorAuthEntities = $userMultiFactorAuthQuery->find();

        if ($userMultiFactorAuthEntities->count() === 0) {
            return new MultiFactorAuthTypesCollectionTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer($userMultiFactorAuthEntities, new MultiFactorAuthTypesCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
    public function getVerifiedCustomerMultiFactorAuthType(CustomerTransfer $customerTransfer): ?string
    {
        return $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodeQuery()
            ->orderByIdCustomerMultiFactorAuthCode(Criteria::DESC)
            ->filterByStatus(MultiFactorAuthConstants::CODE_VERIFIED)
            ->useSpyCustomerMultiFactorAuthQuery()
                ->filterByFkCustomer($customerTransfer->getIdCustomerOrFail())
            ->endUse()
            ->select([SpyCustomerMultiFactorAuthTableMap::COL_TYPE])
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string|null
     */
    public function getVerifiedUserMultiFactorAuthType(UserTransfer $userTransfer): ?string
    {
        return $this->getFactory()
            ->createSpyUserMultiFactorAuthCodeQuery()
            ->orderByIdUserMultiFactorAuthCode(Criteria::DESC)
            ->filterByStatus(MultiFactorAuthConstants::CODE_VERIFIED)
            ->useSpyUserMultiFactorAuthQuery()
                ->filterByFkUser($userTransfer->getIdUserOrFail())
            ->endUse()
            ->select([SpyUserMultiFactorAuthTableMap::COL_TYPE])
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    public function getCustomerCodeEnteringAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int
    {
        return $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodesAttemptsQuery()
            ->filterByFkCustomerMultiFactorAuthCode($multiFactorAuthCodeTransfer->getIdCode())
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    public function getUserCodeEnteringAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int
    {
        return $this->getFactory()
            ->createSpyUserMultiFactorAuthCodesAttemptsQuery()
            ->filterByFkUserMultiFactorAuthCode($multiFactorAuthCodeTransfer->getIdCode())
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findUserMultiFactorAuthCodeByCriteria(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer {
        $userMultiFactorAuthCodeQuery = $this->getFactory()
            ->createSpyUserMultiFactorAuthCodeQuery()
            ->innerJoinSpyUserMultiFactorAuth();

        if ($multiFactorAuthCodeCriteriaTransfer->getCode() !== null) {
            $userMultiFactorAuthCodeQuery->filterByCode($multiFactorAuthCodeCriteriaTransfer->getCode());
        }

        if ($multiFactorAuthCodeCriteriaTransfer->getStatus() !== null) {
            $userMultiFactorAuthCodeQuery->filterByStatus($multiFactorAuthCodeCriteriaTransfer->getStatus());
        }

        $userMultiFactorAuthCodeQuery = $userMultiFactorAuthCodeQuery
            ->useSpyUserMultiFactorAuthQuery()
                ->filterByFkUser($multiFactorAuthCodeCriteriaTransfer->getUserOrFail()->getIdUserOrFail());

        if ($multiFactorAuthCodeCriteriaTransfer->getType() !== null) {
            $userMultiFactorAuthCodeQuery->filterByType($multiFactorAuthCodeCriteriaTransfer->getType());
        }

        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuthCodes|null $userMultiFactorAuthCodeEntity */
        $userMultiFactorAuthCodeEntity = $userMultiFactorAuthCodeQuery->addDescendingOrderByColumn(SpyUserMultiFactorAuthCodesTableMap::COL_ID_USER_MULTI_FACTOR_AUTH_CODE)
            ->endUse()
            ->findOne();

        if ($userMultiFactorAuthCodeEntity === null) {
            return new MultiFactorAuthCodeTransfer();
        }

        $multiFactorAuthCodeTransfer = $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapUserMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer($userMultiFactorAuthCodeEntity, new MultiFactorAuthCodeTransfer());

        return $multiFactorAuthCodeTransfer->setType($userMultiFactorAuthCodeEntity->getSpyUserMultiFactorAuth()->getType());
    }

    /**
     * @param \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthQuery|\Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuthQuery $multiFactorAuthQuery
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function addStatusFilter(
        ModelCriteria $multiFactorAuthQuery,
        MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
    ): ModelCriteria {
        if ($multiFactorAuthCriteriaTransfer->getStatuses() === []) {
            return $multiFactorAuthQuery->filterByStatus(MultiFactorAuthConstants::STATUS_ACTIVE);
        }

        $multiFactorAuthQuery->filterByStatus($multiFactorAuthCriteriaTransfer->getStatuses(), Criteria::IN);

        return $multiFactorAuthQuery;
    }
}
