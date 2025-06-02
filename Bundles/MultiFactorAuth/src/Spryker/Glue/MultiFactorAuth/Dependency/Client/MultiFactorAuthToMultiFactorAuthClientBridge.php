<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;

class MultiFactorAuthToMultiFactorAuthClientBridge implements MultiFactorAuthToMultiFactorAuthClientInterface
{
    /**
     * @var \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface
     */
    protected $multiFactorAuthClient;

    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $multiFactorAuthClient
     */
    public function __construct($multiFactorAuthClient)
    {
        $this->multiFactorAuthClient = $multiFactorAuthClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        return $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer
    {
        return $this->multiFactorAuthClient->validateCustomerCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerMultiFactorAuthStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        return $this->multiFactorAuthClient->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        return $this->multiFactorAuthClient->sendCustomerCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function deactivateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $this->multiFactorAuthClient->deactivateCustomerMultiFactorAuth($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function activateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $this->multiFactorAuthClient->activateCustomerMultiFactorAuth($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findCustomerMultiFactorAuthType(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer {
        return $this->multiFactorAuthClient->findCustomerMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        return $this->multiFactorAuthClient->getPendingActivationCustomerMultiFactorAuthTypes($customerTransfer);
    }
}
