<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Persistence\Mapper;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuth;
use Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes;
use Propel\Runtime\Collection\Collection;

class MultiFactorAuthMapper
{
    /**
     * @param \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuthCodes $customerMultiFactorAuthCode
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function mapMultiFactorAuthCodeEntityToMultiFactorAuthCodeTransfer(
        SpyCustomerMultiFactorAuthCodes $customerMultiFactorAuthCode,
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
    ): MultiFactorAuthCodeTransfer {
        return $multiFactorAuthCodeTransfer->fromArray($customerMultiFactorAuthCode->toArray(), true)
            ->setIdCode($customerMultiFactorAuthCode->getIdCustomerMultiFactorAuthCode());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $multiFactorAuthEntities
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function mapMultiFactorAuthEntitiesToMultiFactorAuthTypesCollectionTransfer(
        Collection $multiFactorAuthEntities,
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
    ): MultiFactorAuthTypesCollectionTransfer {
        foreach ($multiFactorAuthEntities as $multiFactorAuthEntity) {
            $multiFactorAuthTransfer = new MultiFactorAuthTransfer();
            $multiFactorAuthTransfer->fromArray($multiFactorAuthEntity->toArray(), true);

            $multiFactorAuthTypesCollectionTransfer->addMultiFactorAuth($multiFactorAuthTransfer);
        }

        return $multiFactorAuthTypesCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     * @param \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuth $customerMultiFactorAuth
     *
     * @return \Orm\Zed\MultiFactorAuth\Persistence\SpyCustomerMultiFactorAuth
     */
    public function mapMultiFactorAuthTransferToCustomerMultiFactorAuthEntity(
        MultiFactorAuthTransfer $multiFactorAuthTransfer,
        SpyCustomerMultiFactorAuth $customerMultiFactorAuth
    ): SpyCustomerMultiFactorAuth {
        $customerMultiFactorAuth->fromArray($multiFactorAuthTransfer->toArray());

        if ($multiFactorAuthTransfer->getCustomer() !== null) {
            $customerMultiFactorAuth->setFkCustomer($multiFactorAuthTransfer->getCustomerOrFail()->getIdCustomerOrFail());
        }

        return $customerMultiFactorAuth;
    }
}
