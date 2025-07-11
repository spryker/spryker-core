<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Verify;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthTypeVerifyProcessor implements MultiFactorAuthTypeVerifyProcessorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface $multiFactorAuthValidator
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected MultiFactorAuthToUserFacadeInterface $userFacade,
        protected MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder,
        protected MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder,
        protected MultiFactorAuthValidatorInterface $multiFactorAuthValidator
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function verifyMultiFactorAuth(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): GlueResponseTransfer {
        $multiFactorAuthCode = array_key_exists(
            strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE),
            $glueRequestTransfer->getMeta(),
        ) ? $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0] : null;
        if ($multiFactorAuthCode === null) {
            return $this->multiFactorAuthResponseBuilder->createMissingMultiFactorAuthCodeError();
        }
        $errorResponse = $this->multiFactorAuthValidator->validateMultiFactorAuthType($glueRequestTransfer, $restMultiFactorAuthAttributesTransfer);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $multiFactorAuthType = $restMultiFactorAuthAttributesTransfer->getTypeOrFail();
        $userCollectionTransfer = $this->userFacade->getUserCollection($this->multiFactorAuthTransferBuilder->createUserCriteriaTransfer([(int)$glueRequestTransfer->getRequestUser()?->getSurrogateIdentifier()]));
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())->setUser($userTransfer);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade->getUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        if ($this->multiFactorAuthValidator->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthType) === true) {
            return $this->multiFactorAuthResponseBuilder->createAlreadyActivatedMultiFactorAuthError();
        }
        if ($this->multiFactorAuthValidator->isPendingActivationMultiFactorAuthType($userTransfer, $multiFactorAuthType) === false) {
            return $this->multiFactorAuthResponseBuilder->createNotFoundTypeErrorResponse();
        }

        $multiFactorAuthCodeTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode);
        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthType,
            $userTransfer,
            $multiFactorAuthCodeTransfer,
            MultiFactorAuthConstants::STATUS_ACTIVE,
        );

        if ($this->multiFactorAuthValidator->isMultiFactorAuthCodeValid($multiFactorAuthCode, $userTransfer, $multiFactorAuthTransfer, [MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION]) === false) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $this->multiFactorAuthFacade->activateUserMultiFactorAuth($multiFactorAuthTransfer);

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
