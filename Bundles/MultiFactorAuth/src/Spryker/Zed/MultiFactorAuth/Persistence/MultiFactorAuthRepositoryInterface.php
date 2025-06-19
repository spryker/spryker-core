<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MultiFactorAuth\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MultiFactorAuthRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function getCustomerCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer, array $additionalStatuses = []): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function getUserCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getUserMultiFactorAuthTypes(UserTransfer $userTransfer, array $additionalStatuses = []): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
    public function getVerifiedCustomerMultiFactorAuthType(CustomerTransfer $customerTransfer): ?string;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string|null
     */
    public function getVerifiedUserMultiFactorAuthType(UserTransfer $userTransfer): ?string;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    public function getCustomerCodeEnteringAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    public function getUserCodeEnteringAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findCustomerMultiFactorAuthCodeByCriteria(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationCustomerMultiFactorAuthTypes(CustomerTransfer $customerTransfer): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function findUserMultiFactorAuthCodeByCriteria(
        MultiFactorAuthCodeCriteriaTransfer $multiFactorAuthCodeCriteriaTransfer
    ): MultiFactorAuthCodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getPendingActivationUserMultiFactorAuthTypes(UserTransfer $userTransfer): MultiFactorAuthTypesCollectionTransfer;
}
