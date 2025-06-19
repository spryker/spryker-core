<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Activate;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Throwable;

class MultiFactorAuthTypeActivateProcessor implements MultiFactorAuthTypeActivateProcessorInterface
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
    public function activateMultiFactorAuth(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): GlueResponseTransfer {
        $errorResponse = $this->multiFactorAuthValidator->validateMultiFactorAuthType($glueRequestTransfer, $restMultiFactorAuthAttributesTransfer);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $multiFactorAuthType = $restMultiFactorAuthAttributesTransfer->getTypeOrFail();
        $userCollectionTransfer = $this->userFacade->getUserCollection($this->multiFactorAuthTransferBuilder->createUserCriteriaTransfer([(int)$glueRequestTransfer->getRequestUser()?->getSurrogateIdentifier()]));
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade
            ->getUserMultiFactorAuthTypes($userTransfer);

        if ($this->multiFactorAuthValidator->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthType) === true) {
            return $this->multiFactorAuthResponseBuilder->createAlreadyActivatedMultiFactorAuthError();
        }

        if ($this->assertTheCodeIsMissing($glueRequestTransfer, $multiFactorAuthTypesCollectionTransfer)) {
            return $this->multiFactorAuthResponseBuilder->createMissingMultiFactorAuthCodeError();
        }

        if ($this->assertTheProvidedCodeIsNotApplicable($glueRequestTransfer, $multiFactorAuthTypesCollectionTransfer, $userTransfer)) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthType,
            $userTransfer,
            null,
            MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION,
        );

        $this->multiFactorAuthFacade->activateUserMultiFactorAuth($multiFactorAuthTransfer);

        return $this->safelySendActivationCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     *
     * @return bool
     */
    protected function assertTheCodeIsMissing(
        GlueRequestTransfer $glueRequestTransfer,
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
    ): bool {
        return $this->hasExistingMultiFactorAuth($multiFactorAuthTypesCollectionTransfer) && !isset($glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function assertTheProvidedCodeIsNotApplicable(
        GlueRequestTransfer $glueRequestTransfer,
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer,
        UserTransfer $userTransfer
    ): bool {
        $hasExistingMultiFactorAuth = $this->hasExistingMultiFactorAuth($multiFactorAuthTypesCollectionTransfer);

        if (!$hasExistingMultiFactorAuth && !isset($glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)])) {
            return false;
        }

        $isCodeValid = $this->isMultiFactorAuthActivationCodeValid($glueRequestTransfer, $userTransfer);

        if (!$hasExistingMultiFactorAuth && $isCodeValid) {
            return true;
        }

        return $hasExistingMultiFactorAuth && !$isCodeValid;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     *
     * @return bool
     */
    protected function hasExistingMultiFactorAuth(MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer): bool
    {
        if (count($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()) === 0) {
            return false;
        }

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthType) {
            if ($multiFactorAuthType->getStatus() === MultiFactorAuthConstants::STATUS_ACTIVE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthActivationCodeValid(
        GlueRequestTransfer $glueRequestTransfer,
        UserTransfer $userTransfer
    ): bool {
        $multiFactorAuthCode = $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0];
        $multiFactorAuthCodeTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode);

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)->setUser($userTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthFacade
            ->findUserMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if ($multiFactorAuthCodeWithTypeTransfer->getType() === null) {
            return false;
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail(),
            $userTransfer,
            $multiFactorAuthCodeTransfer,
        );

        return $this->multiFactorAuthValidator->isMultiFactorAuthCodeValid($multiFactorAuthCode, $userTransfer, $multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function safelySendActivationCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): GlueResponseTransfer
    {
        try {
            $this->multiFactorAuthFacade->sendUserCode(
                $multiFactorAuthTransfer->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE),
            );
        } catch (Throwable $e) {
            return $this->multiFactorAuthResponseBuilder->createSendingCodeError();
        }

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
