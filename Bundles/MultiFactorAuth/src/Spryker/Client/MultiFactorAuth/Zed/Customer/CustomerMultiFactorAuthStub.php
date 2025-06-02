<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiFactorAuth\Zed\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface;

class CustomerMultiFactorAuthStub implements CustomerMultiFactorAuthStubInterface
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface $zedStub
     */
    public function __construct(protected MultiFactorAuthToZedRequestClientInterface $zedStub)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer */
        $multiFactorAuthTypesCollectionTransfer = $this->zedStub->call('/multi-factor-auth/gateway/get-customer-multi-factor-auth-types', $customerTransfer);

        return $multiFactorAuthTypesCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/send-customer-code', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerMultiFactorAuthStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer */
        $multiFactorAuthValidationResponseTransfer = $this->zedStub->call('/multi-factor-auth/gateway/validate-customer-multi-factor-auth-status', $multiFactorAuthValidationRequestTransfer);

        return $multiFactorAuthValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer */
        $multiFactorAuthValidationResponseTransfer = $this->zedStub->call('/multi-factor-auth/gateway/validate-customer-code', $multiFactorAuthTransfer);

        return $multiFactorAuthValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function activateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/activate-customer-multi-factor-auth', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function deactivateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/deactivate-customer-multi-factor-auth', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findCustomerMultiFactorAuthType(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer */
        $multiFactorAuthCodeTransfer = $this->zedStub->call('/multi-factor-auth/gateway/find-customer-multi-factor-auth-type', $multiFactorAuthCodeCriteriaTransfer);

        return $multiFactorAuthCodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer */
        $multiFactorAuthTypesCollectionTransfer = $this->zedStub->call('/multi-factor-auth/gateway/get-pending-activation-customer-multi-factor-auth-types', $customerTransfer);

        return $multiFactorAuthTypesCollectionTransfer;
    }
}
