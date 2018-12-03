<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Validation;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestApiValidator implements RestApiValidatorInterface
{
    protected const CUSTOMERS_GENDER_ENUM_MALE = 'Male';
    protected const CUSTOMERS_GENDER_ENUM_FEMALE = 'Female';

    public const CUSTOMERS_GENDERS_ENUM = [
        self::CUSTOMERS_GENDER_ENUM_MALE,
        self::CUSTOMERS_GENDER_ENUM_FEMALE,
    ];

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface
     */
    protected $restApiErrorProcessor;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface $restApiErrorProcessor
     */
    public function __construct(RestApiErrorProcessorInterface $restApiErrorProcessor)
    {
        $this->restApiErrorProcessor = $restApiErrorProcessor;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function validateCustomerResponseTransfer(
        CustomerResponseTransfer $customerResponseTransfer,
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->restApiErrorProcessor->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->restApiErrorProcessor->addCustomerUnauthorizedError($restResponse);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function validatePassword(
        RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        if (!$this->assertPasswordNotEmpty($passwordAttributesTransfer)) {
            return $this->restApiErrorProcessor->addPasswordNotValidError($restResponse);
        }

        if (!$this->assertPasswordsAreIdentical($passwordAttributesTransfer)) {
            return $this->restApiErrorProcessor->addPasswordsDoNotMatchError(
                $restResponse,
                RestCustomerPasswordAttributesTransfer::NEW_PASSWORD,
                RestCustomerPasswordAttributesTransfer::CONFIRM_PASSWORD
            );
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        $customerResource = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS) ?? $restRequest->getResource();

        return $restRequest->getUser()->getNaturalIdentifier() === $customerResource->getId();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return bool
     */
    protected function assertPasswordsAreIdentical(RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): bool
    {
        return $passwordAttributesTransfer->getNewPassword() === $passwordAttributesTransfer->getConfirmPassword();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return bool
     */
    protected function assertPasswordNotEmpty(RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): bool
    {
        return mb_strlen($passwordAttributesTransfer->getNewPassword()) > 1;
    }
}
