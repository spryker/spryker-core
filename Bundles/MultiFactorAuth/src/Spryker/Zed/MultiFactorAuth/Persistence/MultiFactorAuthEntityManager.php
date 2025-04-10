<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\Map\SpyCustomerMultiFactorAuthCodesTableMap;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthPersistenceFactory getFactory()
 */
class MultiFactorAuthEntityManager extends AbstractEntityManager implements MultiFactorAuthEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function saveCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $customerMultiFactorAuthEntity = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomer())
            ->filterByType($multiFactorAuthTransfer->getType())
            ->findOne();

        if ($customerMultiFactorAuthEntity === null) {
            return;
        }

        $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodeEntity()
            ->setCode($multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->getCodeOrFail())
            ->setFkCustomerMultiFactorAuth($customerMultiFactorAuthEntity->getIdCustomerMultiFactorAuth())
            ->setExpirationDate($multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->getExpirationDateOrFail())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function updateCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        /** @var \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodesQuery $customerMultiFactorAuthCodesQuery */
        $customerMultiFactorAuthCodesQuery = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodeQuery()
            ->useSpyCustomerMultiFactorAuthQuery()
                ->filterByFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomer())
                ->filterByType($multiFactorAuthTransfer->getType())
            ->endUse();

        $customerMultiFactorAuthCodeEntity = $customerMultiFactorAuthCodesQuery
            ->filterByCode($multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->getCode())
            ->addDescendingOrderByColumn(SpyCustomerMultiFactorAuthCodesTableMap::COL_ID_CUSTOMER_MULTI_FACTOR_AUTH_CODE)
            ->findOne();

        if ($customerMultiFactorAuthCodeEntity === null) {
            return;
        }

        $customerMultiFactorAuthCodeEntity->setStatus(
            $multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->getStatus(),
        )->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function saveCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $customerMultiFactorAuthEntity = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomer())
            ->filterByType($multiFactorAuthTransfer->getType())
            ->findOne();

        if ($customerMultiFactorAuthEntity === null) {
            $customerMultiFactorAuthEntity = $this->getFactory()
                ->createMultiFactorAuthMapper()
                ->mapMultiFactorAuthTransferToCustomerMultiFactorAuthEntity(
                    $multiFactorAuthTransfer,
                    $this->getFactory()->createSpyCustomerMultiFactorAuthEntity(),
                );
        }

        $customerMultiFactorAuthEntity->setStatus($multiFactorAuthTransfer->getStatusOrFail())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function deleteCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $customerMultiFactorAuthEntity = $this->getFactory()
            ->createSpyCustomerMultiFactorAuthQuery()
            ->filterByFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomer())
            ->filterByType($multiFactorAuthTransfer->getType())
            ->findOne();

        if ($customerMultiFactorAuthEntity === null) {
            return;
        }

        $customerMultiFactorAuthEntity->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return void
     */
    public function saveCustomerMultiFactorAuthCodeAttempt(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): void
    {
        $this->getFactory()
            ->createSpyCustomerMultiFactorAuthCodesAttemptsEntity()
            ->setFkCustomerMultiFactorAuthCode($multiFactorAuthCodeTransfer->getIdCodeOrFail())
            ->save();
    }
}
