<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\Verify;

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

class MultiFactorAuthTypeVerifyProcessor implements MultiFactorAuthTypeVerifyProcessorInterface
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
    public function verifyMultiFactorAuth(
        RestRequestInterface $restRequest,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): RestResponseInterface {
        $multiFactorAuthCode = $restRequest->getHttpRequest()->headers->get(MultiFactorAuthConfig::HEADER_MULTI_FACTOR_AUTH_CODE);
        if ($multiFactorAuthCode === null) {
            return $this->multiFactorAuthResponseBuilder->createMissingMultiFactorAuthCodeError();
        }
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
        if ($this->multiFactorAuthValidator->isPendingActivationMultiFactorAuthType($customerTransfer, $multiFactorAuthType) === false) {
            return $this->multiFactorAuthResponseBuilder->createNotFoundTypeErrorResponse();
        }

        $multiFactorAuthCodeTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode);
        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
            $multiFactorAuthType,
            $customerTransfer,
            $multiFactorAuthCodeTransfer,
            MultiFactorAuthConstants::STATUS_ACTIVE,
        );

        if ($this->multiFactorAuthValidator->isMultiFactorAuthCodeValid($multiFactorAuthCode, $customerTransfer, $multiFactorAuthTransfer) === false) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $this->multiFactorAuthClient->activateCustomerMultiFactorAuth($multiFactorAuthTransfer);

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
