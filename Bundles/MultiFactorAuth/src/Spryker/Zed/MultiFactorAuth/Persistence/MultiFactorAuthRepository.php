<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthCodesTableMap;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthTableMap;
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
            ->mapMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer($customerMultiFactorAuthCodeEntity, new MultiFactorAuthCodeTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        $customerMultiFactorAuthEntities = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->filterByStatus(MultiFactorAuthConstants::STATUS_ACTIVE)
            ->find();

        if ($customerMultiFactorAuthEntities->count() === 0) {
            return new MultiFactorAuthTypesCollectionTransfer();
        }

        return $this->getFactory()
            ->createMultiFactorAuthMapper()
            ->mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer($customerMultiFactorAuthEntities, new MultiFactorAuthTypesCollectionTransfer());
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
}
