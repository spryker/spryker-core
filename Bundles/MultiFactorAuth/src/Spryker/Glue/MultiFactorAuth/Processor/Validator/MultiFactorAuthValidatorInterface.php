<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\Validator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface MultiFactorAuthValidatorInterface
{
    /**
     * @param string $multiFactorAuthCode
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    public function isMultiFactorAuthCodeValid(
        string $multiFactorAuthCode,
        CustomerTransfer $customerTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface|null
     */
    public function validateMultiFactorAuthType(
        RestRequestInterface $restRequest,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): ?RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    public function isMultiFactorAuthCodeVerified(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool;
}
