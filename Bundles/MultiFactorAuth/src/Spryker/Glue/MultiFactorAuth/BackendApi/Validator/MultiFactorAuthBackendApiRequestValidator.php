<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiFactorAuthBackendApiRequestValidator implements MultiFactorAuthBackendApiRequestValidatorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $multiFactorAuthConfig
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected MultiFactorAuthToUserFacadeInterface $userFacade,
        protected MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder,
        protected MultiFactorAuthConfig $multiFactorAuthConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())->setIsValid(true);
        if ($this->shouldSkipValidation($glueRequestTransfer)) {
            return $glueRequestValidationTransfer;
        }
        $userCollectionTransfer = $this->userFacade->getUserCollection($this->multiFactorAuthTransferBuilder->createUserCriteriaTransfer([(int)$glueRequestTransfer->getRequestUser()?->getSurrogateIdentifier()]));
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade
            ->getUserMultiFactorAuthTypes($userTransfer);
        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return $glueRequestValidationTransfer;
        }

        if (!$this->hasMultiFactorAuthCodeHeader($glueRequestTransfer)) {
            return $this->createMissingMultiFactorAuthCodeError($glueRequestValidationTransfer);
        }

        $multiFactorAuthCode = $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0];

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)->setUser($userTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthFacade
            ->findUserMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if (
            $multiFactorAuthCodeWithTypeTransfer->getType() === null ||
            $this->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail()) === false
        ) {
            return $this->createInvalidMultiFactorAuthCodeError($glueRequestValidationTransfer);
        }

        if ($this->isMultiFactorAuthCodeValid($multiFactorAuthCodeWithTypeTransfer, $userTransfer) === false) {
            return $this->createInvalidMultiFactorAuthCodeError($glueRequestValidationTransfer);
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     * @param string $multiFactorAuthType
     *
     * @return bool
     */
    protected function isActivatedMultiFactorAuthType(
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer,
        string $multiFactorAuthType
    ): bool {
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $activatedMultiFactorAuthType) {
            if ($activatedMultiFactorAuthType->getTypeOrFail() === $multiFactorAuthType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function shouldSkipValidation(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $resourceType = $glueRequestTransfer->getResourceOrFail()->getType();

        return $resourceType === null
            || !$glueRequestTransfer->getRequestUser()
            || $glueRequestTransfer->getMethod() === Request::METHOD_OPTIONS
            || $glueRequestTransfer->getMethod() === Request::METHOD_GET
            || !$this->isRestApiMultiFactorAuthProtectedResource($resourceType);
    }

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    protected function isRestApiMultiFactorAuthProtectedResource(string $resourceType): bool
    {
        return in_array($resourceType, $this->multiFactorAuthConfig->getMultiFactorAuthProtectedBackendResources(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function hasMultiFactorAuthCodeHeader(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return array_key_exists(strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE), $glueRequestTransfer->getMeta()) &&
            $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0] !== null &&
            $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0] !== '';
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createMissingMultiFactorAuthCodeError(GlueRequestValidationTransfer $glueRequestValidationTransfer): GlueRequestValidationTransfer
    {
        $glueErrorTransfer = new GlueErrorTransfer();
        $glueErrorTransfer
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_MISSING)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_MISSING);

        return $glueRequestValidationTransfer
            ->setIsValid(false)
            ->addError($glueErrorTransfer)
            ->setStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createInvalidMultiFactorAuthCodeError(GlueRequestValidationTransfer $glueRequestValidationTransfer): GlueRequestValidationTransfer
    {
        $glueErrorTransfer = new GlueErrorTransfer();
        $glueErrorTransfer
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_INVALID)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_INVALID);

        return $glueRequestValidationTransfer
            ->setIsValid(false)
            ->addError($glueErrorTransfer)
            ->setStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    protected function buildMultiFactorAuthTransfer(
        UserTransfer $userTransfer,
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
    ): MultiFactorAuthTransfer {
        return (new MultiFactorAuthTransfer())
            ->setType($multiFactorAuthCodeTransfer->getTypeOrFail())
            ->setUser($userTransfer)
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeWithTypeTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthCodeValid(
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeWithTypeTransfer,
        UserTransfer $userTransfer
    ): bool {
        $multiFactorAuthTransfer = $this->buildMultiFactorAuthTransfer(
            $userTransfer,
            $multiFactorAuthCodeWithTypeTransfer,
        );
        if ($multiFactorAuthCodeWithTypeTransfer->getIdCode() === null) {
            return false;
        }

        if ($multiFactorAuthCodeWithTypeTransfer->getStatusOrFail() === MultiFactorAuthConstants::STATUS_ACTIVE) {
            $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);
            $multiFactorAuthValidationResponseTransfer = $this->multiFactorAuthFacade->validateUserMultiFactorAuthStatus(
                $multiFactorAuthValidationRequestTransfer,
            );

            return $multiFactorAuthValidationResponseTransfer->getIsRequired() === false;
        }

        return $this->isMultiFactorAuthCodeVerified($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthCodeVerified(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool
    {
        $validationResponse = $this->multiFactorAuthFacade->validateUserCode($multiFactorAuthTransfer);

        return $validationResponse->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED;
    }
}
