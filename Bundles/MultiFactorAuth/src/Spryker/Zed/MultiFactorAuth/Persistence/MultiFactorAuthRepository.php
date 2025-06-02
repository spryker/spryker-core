<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthCodesTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyUserMultiFactorAuthCodesTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyUserMultiFactorAuthTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
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

        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes|null $customerMultiFactorAuthCodeEntity */
        $customerMultiFactorAuthCodeEntity = $customerMultiFactorAuthCodeQuery
            ->useSpyCustomerMultiFactorAuthQuery()
                ->filterByFkCustomer($multiFactorAuthCodeCriteriaTransfer->getCustomerOrFail()->getIdCustomer())
                ->addDescendingOrderByColumn(SpyCustomerMultiFactorAuthCodesTableMap::COL_ID_CUSTOMER_MULTI_FACTOR_AUTH_CODE)
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer, array $additionalStatuses = []): MultiFactorAuthTypesCollectionTransfer
    {
        $statuses = array_unique(array_merge(
            [MultiFactorAuthConstants::STATUS_ACTIVE],
            $additionalStatuses,
        ));

        $customerMultiFactorAuthEntities = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->filterByStatus($statuses, Criteria::IN)
            ->find();

        if ($customerMultiFactorAuthEntities->count() === 0) {
            return new MultiFactorAuthTypesCollectionTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer($customerMultiFactorAuthEntities, new MultiFactorAuthTypesCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getUserMultiFactorAuthTypes(UserTransfer $userTransfer, array $additionalStatuses = []): MultiFactorAuthTypesCollectionTransfer
    {
        $statuses = array_unique(array_merge(
            [MultiFactorAuthConstants::STATUS_ACTIVE],
            $additionalStatuses,
        ));

        $userMultiFactorAuthEntities = $this->getFactory()
            ->createSpyUserMultiFactorAuthQuery()
            ->filterByFkUser($userTransfer->getIdUser())
            ->filterByStatus($statuses, Criteria::IN)
            ->find();

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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        $customerMultiFactorAuthEntities = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->filterByStatus(MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION)
            ->find();

        if ($customerMultiFactorAuthEntities->count() === 0) {
            return new MultiFactorAuthTypesCollectionTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer($customerMultiFactorAuthEntities, new MultiFactorAuthTypesCollectionTransfer());
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
}
