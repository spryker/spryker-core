<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Dependency\Facade;

use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MultiFactorAuthToMultiFactorAuthFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer;

    /**
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
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function activateUserMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function deactivateUserMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findUserMultiFactorAuthType(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationUserMultiFactorAuthTypes(UserTransfer $userTransfer): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getUserMultiFactorAuthTypes(UserTransfer $userTransfer, array $additionalStatuses = []): MultiFactorAuthTypesCollectionTransfer;
}
