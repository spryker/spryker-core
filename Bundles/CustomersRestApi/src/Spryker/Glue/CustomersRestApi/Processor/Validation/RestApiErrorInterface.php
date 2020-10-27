<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Validation;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface RestApiErrorInterface
{
    public const ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';
    public const ERROR_MESSAGE_CUSTOMER_EMAIL_INVALID = 'customer.email.format.invalid';
    public const ERROR_MESSAGE_CUSTOMER_EMAIL_LENGTH_EXCEEDED = 'customer.email.length.exceeded';
    public const ERROR_CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';
    public const ERROR_CUSTOMER_PASSWORD_TOO_LONG = 'customer.password.error.max_length';
    public const ERROR_CUSTOMER_PASSWORD_TOO_SHORT = 'customer.password.error.min_length';
    public const ERROR_CUSTOMER_PASSWORD_DIGIT_REQUIRED = 'customer.password.error.digit';
    public const ERROR_CUSTOMER_PASSWORD_UPPER_CASE_REQUIRED = 'customer.password.error.upper_case';
    public const ERROR_CUSTOMER_PASSWORD_LOWER_CASE_REQUIRED = 'customer.password.error.lower_case';
    public const ERROR_CUSTOMER_PASSWORD_SPECIAL_REQUIRED = 'customer.password.error.special';
    public const ERROR_CUSTOMER_PASSWORD_CHAR_NOT_ALLOWED = 'customer.password.error.invalid';
    public const ERROR_CUSTOMER_PASSWORD_SEQUNECE_TOO_LONG = 'customer.password.error.sequence';
    public const ERROR_CUSTOMER_PASSWORD_BLACKLIST = 'customer.password.error.blacklist';

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerAlreadyExistsError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerCantRegisterMessageError(RestResponseInterface $restResponse, string $errorMessage): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerNotFoundError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addAddressNotFoundError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerReferenceMissingError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addPasswordsNotMatchError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addPasswordChangeError(RestResponseInterface $restResponse, string $errorMessage): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addPasswordNotValidError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addAddressNotSavedError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerNotSavedError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCustomerUnauthorizedError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addAddressUuidMissingError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addNotAcceptedTermsError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addNotValidGenderError(RestResponseInterface $restResponse): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $passwordFieldName
     * @param string $passwordConfirmFieldName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addPasswordsDoNotMatchError(
        RestResponseInterface $restResponse,
        string $passwordFieldName,
        string $passwordConfirmFieldName
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processCustomerErrorOnRegistration(
        RestResponseInterface $restResponse,
        CustomerResponseTransfer $customerResponseTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processCustomerErrorOnUpdate(
        RestResponseInterface $restResponse,
        CustomerResponseTransfer $customerResponseTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processCustomerErrorOnPasswordUpdate(
        RestResponseInterface $restResponse,
        CustomerResponseTransfer $customerResponseTransfer
    ): RestResponseInterface;
}
