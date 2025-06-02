<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\Activate;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthActivateProcessor implements MultiFactorAuthActivateProcessorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthValidatorInterface $multiFactorAuthValidator
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function activateMultiFactorAuth(
        RestRequestInterface $restRequest,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): RestResponseInterface {
        $errorResponse = $this->multiFactorAuthValidator->validateMultiFactorAuthType($restRequest, $restMultiFactorAuthAttributesTransfer);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $multiFactorAuthType = $restMultiFactorAuthAttributesTransfer->getTypeOrFail();
        $customerTransfer = $this->customerClient->getCustomerById((int)$restRequest->getRestUser()?->getSurrogateIdentifierOrFail());
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient
            ->getCustomerMultiFactorAuthTypes($customerTransfer);

        if ($this->multiFactorAuthValidator->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthType) === true) {
            return $this->multiFactorAuthResponseBuilder->createAlreadyActivatedMultiFactorAuthError();
        }

        if ($this->hasExistingMultiFactorAuth($multiFactorAuthTypesCollectionTransfer) && $restRequest->getHttpRequest()->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE) === null) {
            return $this->multiFactorAuthResponseBuilder->createMissingMultiFactorAuthCodeError();
        }

        if ($this->hasExistingMultiFactorAuth($multiFactorAuthTypesCollectionTransfer) && !$this->isMultiFactorAuthActivationCodeValid($restRequest, $customerTransfer)) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthType,
            $customerTransfer,
            null,
            MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION,
        );

        $this->multiFactorAuthClient->activateCustomerMultiFactorAuth($multiFactorAuthTransfer);
        $this->multiFactorAuthClient->sendCustomerCode($multiFactorAuthTransfer->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE));

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     *
     * @return bool
     */
    protected function hasExistingMultiFactorAuth(MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer): bool
    {
        return count($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()) > 0;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthActivationCodeValid(
        RestRequestInterface $restRequest,
        CustomerTransfer $customerTransfer
    ): bool {
        $multiFactorAuthCode = (string)$restRequest->getHttpRequest()->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE);
        $multiFactorAuthCodeTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode);

        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)->setCustomer($customerTransfer);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthClient
            ->findCustomerMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if ($multiFactorAuthCodeWithTypeTransfer->getType() === null) {
            return false;
        }

        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail(),
            $customerTransfer,
            $multiFactorAuthCodeTransfer,
        );

        return $this->multiFactorAuthValidator->isMultiFactorAuthCodeVerified($multiFactorAuthTransfer);
    }
}
