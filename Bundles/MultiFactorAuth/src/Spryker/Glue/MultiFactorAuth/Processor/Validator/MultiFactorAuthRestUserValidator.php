<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\Validator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiFactorAuthRestUserValidator implements MultiFactorAuthRestUserValidatorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $config
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient,
        protected MultiFactorAuthToCustomerClientInterface $customerClient,
        protected MultiFactorAuthConfig $config,
        protected RestResourceBuilderInterface $restResourceBuilder
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if ($this->shouldSkipValidation($restRequest)) {
            return null;
        }
        $customerTransfer = $this->customerClient->getCustomerById((int)$restRequest->getRestUser()?->getSurrogateIdentifierOrFail());
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient
            ->getCustomerMultiFactorAuthTypes($customerTransfer);
        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return null;
        }

        if (!$this->hasMultiFactorAuthCodeHeader($restRequest->getHttpRequest())) {
            return $this->createMissingMultiFactorAuthCodeError();
        }

        $multiFactorAuthCode = (string)$restRequest->getHttpRequest()->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE);

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)->setCustomer($customerTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthClient
            ->findCustomerMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if (
            $multiFactorAuthCodeWithTypeTransfer->getType() === null ||
            $this->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail()) === false
        ) {
            return $this->createInvalidMultiFactorAuthCodeError();
        }

        if ($this->isMultiFactorAuthCodeValid($multiFactorAuthCodeWithTypeTransfer, $customerTransfer) === false) {
            return $this->createInvalidMultiFactorAuthCodeError();
        }

        return null;
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function shouldSkipValidation(RestRequestInterface $restRequest): bool
    {
        $resourceType = $restRequest->getResource()->getType();

        return !$restRequest->getRestUser()
            || $restRequest->getHttpRequest()->getMethod() === Request::METHOD_OPTIONS
            || $restRequest->getHttpRequest()->getMethod() === Request::METHOD_GET
            || !$this->isRestApiMultiFactorAuthProtectedResource($resourceType);
    }

    /**
     * @param string $resourceType
     *
     * @return bool
     */
    protected function isRestApiMultiFactorAuthProtectedResource(string $resourceType): bool
    {
        return in_array($resourceType, $this->config->getRestApiMultiFactorAuthProtectedResources(), true);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function hasMultiFactorAuthCodeHeader(Request $request): bool
    {
        return $request->headers->has(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE) &&
            $request->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE) !== null &&
            $request->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE) !== '';
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createMissingMultiFactorAuthCodeError(): RestErrorMessageTransfer
    {
        $restErrorMessageTransfer = new RestErrorMessageTransfer();
        $restErrorMessageTransfer->setStatus(Response::HTTP_FORBIDDEN);
        $restErrorMessageTransfer->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_MISSING);
        $restErrorMessageTransfer->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_MISSING);

        return $restErrorMessageTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createInvalidMultiFactorAuthCodeError(): RestErrorMessageTransfer
    {
        $restErrorMessageTransfer = new RestErrorMessageTransfer();
        $restErrorMessageTransfer->setStatus(Response::HTTP_FORBIDDEN);
        $restErrorMessageTransfer->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_INVALID);
        $restErrorMessageTransfer->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_INVALID);

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function getMultiFactorAuthCode(RestRequestInterface $restRequest): ?string
    {
        return $restRequest->getHttpRequest()->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE);
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
