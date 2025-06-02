<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MultiFactorAuthFacadeInterface
{
    /**
     * Specification:
     * - Validates a multi-factor authentication code for a customer.
     * - The validation will be handled by a selected multi-factor authentication strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer;

    /**
     * Specification:
     * - Validates whether the multi-factor authentication method is enabled for the provided customer.
     * - The validation will be handled by a selected multi-factor authentication strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateCustomerMultiFactorAuthStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer;

    /**
     * Specification:
     * - Activates multi-factor authentication for a customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function activateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * Specification:
     * - Deactivates multi-factor authentication for a customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function deactivateCustomerMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * Specification:
     * - Sends a multi-factor authentication code to the customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;

    /**
     * Specification:
     * - Returns multi-factor authentication code by criteria.
     * - Searches from the end of the codes list.
     * - If no code found, returns empty transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findCustomerMultiFactorAuthType(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer;

    /**
     * Specification:
     *  - Returns pending activation multi-factor authentication types for the provided customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer;

    /**
     * Specification:
     * - Validates a multi-factor authentication code for a user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer;

    /**
     * Specification:
     * - Validates whether the multi-factor authentication method is enabled for the provided user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateUserMultiFactorAuthStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer,
        array $additionalStatuses = []
    ): MultiFactorAuthValidationResponseTransfer;

    /**
     * Specification:
     * - Activates multi-factor authentication for a user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function activateUserMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * Specification:
     * - Deactivates multi-factor authentication for a user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function deactivateUserMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * Specification:
     * - Sends a multi-factor authentication code to the user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;

    /**
     * Specification:
     * - Returns available multi-factor authentication types for the provided user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAvailableUserMultiFactorAuthTypes(
        UserTransfer $userTransfer,
        array $userMultiFactorAuthPlugins
    ): MultiFactorAuthTypesCollectionTransfer;
}
