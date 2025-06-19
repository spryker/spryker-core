<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiFactorAuthStorefrontApiRequestValidator implements MultiFactorAuthStorefrontApiRequestValidatorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $multiFactorAuthConfig
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient,
        protected MultiFactorAuthToCustomerClientInterface $customerClient,
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
        $customerTransfer = $this->customerClient->getCustomerById((int)$glueRequestTransfer->getRequestCustomer()?->getSurrogateIdentifierOrFail());
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient
            ->getCustomerMultiFactorAuthTypes($customerTransfer);
        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return $glueRequestValidationTransfer;
        }

        if (!$this->hasMultiFactorAuthCodeHeader($glueRequestTransfer)) {
            return $this->createMissingMultiFactorAuthCodeError($glueRequestValidationTransfer);
        }

        $multiFactorAuthCode = $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0];

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)->setCustomer($customerTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthClient
            ->findCustomerMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if (
            $multiFactorAuthCodeWithTypeTransfer->getType() === null ||
            $this->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail()) === false
        ) {
            return $this->createInvalidMultiFactorAuthCodeError($glueRequestValidationTransfer);
        }

        if ($this->isMultiFactorAuthCodeValid($multiFactorAuthCodeWithTypeTransfer, $customerTransfer) === false) {
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
            || !$glueRequestTransfer->getRequestCustomer()
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
        return in_array($resourceType, $this->multiFactorAuthConfig->getMultiFactorAuthProtectedStorefrontResources(), true);
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    protected function buildMultiFactorAuthTransfer(
        CustomerTransfer $customerTransfer,
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
    ): MultiFactorAuthTransfer {
        return (new MultiFactorAuthTransfer())
            ->setType($multiFactorAuthCodeTransfer->getTypeOrFail())
            ->setCustomer($customerTransfer)
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeWithTypeTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthCodeValid(
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeWithTypeTransfer,
        CustomerTransfer $customerTransfer
    ): bool {
        $multiFactorAuthTransfer = $this->buildMultiFactorAuthTransfer(
            $customerTransfer,
            $multiFactorAuthCodeWithTypeTransfer,
        );
        if ($multiFactorAuthCodeWithTypeTransfer->getIdCode() === null) {
            return false;
        }

        if ($multiFactorAuthCodeWithTypeTransfer->getStatusOrFail() === MultiFactorAuthConstants::STATUS_ACTIVE) {
            $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);
            $multiFactorAuthValidationResponseTransfer = $this->multiFactorAuthClient->validateCustomerMultiFactorAuthStatus(
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
        $validationResponse = $this->multiFactorAuthClient->validateCustomerCode($multiFactorAuthTransfer);

        return $validationResponse->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED;
    }
}
