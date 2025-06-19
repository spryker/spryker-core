<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Deactivate;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidatorInterface;

class MultiFactorAuthTypeDeactivateProcessor implements MultiFactorAuthTypeDeactivateProcessorInterface
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
    public function deactivateMultiFactorAuth(
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
        $customerTransfer = $this->customerClient->getCustomerById((int)$glueRequestTransfer->getRequestCustomer()?->getSurrogateIdentifierOrFail());
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient
            ->getCustomerMultiFactorAuthTypes($customerTransfer);

        if ($this->multiFactorAuthValidator->isActivatedMultiFactorAuthType($multiFactorAuthTypesCollectionTransfer, $multiFactorAuthType) === false) {
            return $this->multiFactorAuthResponseBuilder->createNotFoundTypeErrorResponse();
        }

        $multiFactorAuthCodeTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthCodeTransfer($multiFactorAuthCode);
        $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer($multiFactorAuthType, $customerTransfer, $multiFactorAuthCodeTransfer);

        if ($this->multiFactorAuthValidator->isMultiFactorAuthCodeValid($multiFactorAuthCode, $customerTransfer, $multiFactorAuthTransfer, [], $multiFactorAuthType) === false) {
            return $this->multiFactorAuthResponseBuilder->createInvalidMultiFactorAuthCodeError();
        }

        $this->multiFactorAuthClient->deactivateCustomerMultiFactorAuth($multiFactorAuthTransfer);

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
