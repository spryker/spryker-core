<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;

interface MultiFactorAuthValidatorInterface
{
    /**
     * @param string $multiFactorAuthCode
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     * @param array<int> $additionalStatuses
     * @param string|null $multiFactorAuthType
     *
     * @return bool
     */
    public function isMultiFactorAuthCodeValid(
        string $multiFactorAuthCode,
        CustomerTransfer $customerTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer,
        array $additionalStatuses = [],
        ?string $multiFactorAuthType = null
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $multiFactorAuthType
     *
     * @return bool
     */
    public function isPendingActivationMultiFactorAuthType(
        CustomerTransfer $customerTransfer,
        string $multiFactorAuthType
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     * @param string $multiFactorAuthType
     *
     * @return bool
     */
    public function isActivatedMultiFactorAuthType(
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer,
        string $multiFactorAuthType
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer|null
     */
    public function validateMultiFactorAuthType(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): ?GlueResponseTransfer;
}
