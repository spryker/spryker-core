<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Activate;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Throwable;

class MultiFactorAuthTypeActivateProcessor implements MultiFactorAuthTypeActivateProcessorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidatorInterface $multiFactorAuthValidator
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient,
        protected MultiFactorAuthToCustomerClientInterface $customerClient,
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
        $customerTransfer = $this->customerClient->getCustomerById((int)$glueRequestTransfer->getRequestCustomer()?->getSurrogateIdentifierOrFail());
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())->setCustomer($customerTransfer);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        if ($this->multiFactorAuthValidator->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthType) === true) {
            return $this->multiFactorAuthResponseBuilder->createAlreadyActivatedMultiFactorAuthError();
        }

        if ($this->assertTheCodeIsMissing($glueRequestTransfer, $multiFactorAuthTypesCollectionTransfer)) {
            return $this->multiFactorAuthResponseBuilder->createMissingMultiFactorAuthCodeError();
        }

        if ($this->assertTheProvidedCodeIsNotApplicable($glueRequestTransfer, $multiFactorAuthTypesCollectionTransfer, $customerTransfer)) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthType,
            $customerTransfer,
            null,
            MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION,
        );

        $this->multiFactorAuthClient->activateCustomerMultiFactorAuth($multiFactorAuthTransfer);

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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function assertTheProvidedCodeIsNotApplicable(
        GlueRequestTransfer $glueRequestTransfer,
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer,
        CustomerTransfer $customerTransfer
    ): bool {
        $hasExistingMultiFactorAuth = $this->hasExistingMultiFactorAuth($multiFactorAuthTypesCollectionTransfer);

        if (!$hasExistingMultiFactorAuth && !isset($glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)])) {
            return false;
        }

        $isCodeValid = $this->isMultiFactorAuthActivationCodeValid($glueRequestTransfer, $customerTransfer);

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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthActivationCodeValid(
        GlueRequestTransfer $glueRequestTransfer,
        CustomerTransfer $customerTransfer
    ): bool {
        $multiFactorAuthCode = $glueRequestTransfer->getMeta()[strtolower(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE)][0];

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)
            ->setCustomer($customerTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthClient->findCustomerMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if ($multiFactorAuthCodeWithTypeTransfer->getType() === null) {
            return false;
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail(),
            $customerTransfer,
            $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode),
        );

        return $this->multiFactorAuthValidator->isMultiFactorAuthCodeValid($multiFactorAuthCode, $customerTransfer, $multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function safelySendActivationCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): GlueResponseTransfer
    {
        try {
            $this->multiFactorAuthClient->sendCustomerCode(
                $multiFactorAuthTransfer->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE),
            );
        } catch (Throwable $e) {
            return $this->multiFactorAuthResponseBuilder->createSendingCodeError();
        }

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
