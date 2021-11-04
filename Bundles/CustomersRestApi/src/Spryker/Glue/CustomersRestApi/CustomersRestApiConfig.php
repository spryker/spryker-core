<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class CustomersRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CUSTOMERS = 'customers';

    /**
     * @var string
     */
    public const RESOURCE_ADDRESSES = 'addresses';

    /**
     * @var string
     */
    public const RESOURCE_CUSTOMER_PASSWORD = 'customer-password';

    /**
     * @var string
     */
    public const RESOURCE_FORGOTTEN_PASSWORD = 'customer-forgotten-password';

    /**
     * @var string
     */
    public const RESOURCE_CUSTOMER_RESTORE_PASSWORD = 'customer-restore-password';

    /**
     * @var string
     */
    public const RESOURCE_CUSTOMER_CONFIRMATION = 'customer-confirmation';

    /**
     * @var string
     */
    public const CONTROLLER_CUSTOMER_FORGOTTEN_PASSWORD = 'customer-forgotten-password-resource';

    /**
     * @var string
     */
    public const CONTROLLER_CUSTOMER_RESTORE_PASSWORD = 'customer-restore-password-resource';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS = '400';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS = 'Customer with this email already exists.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER = '401';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_CANT_REGISTER_CUSTOMER = 'Can\'t register a customer.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_NOT_FOUND = '402';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_NOT_FOUND = 'Customer not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND = '403';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND = 'Customer does not have addresses.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_ADDRESS_NOT_FOUND = '404';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_ADDRESS_NOT_FOUND = 'Address was not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_REFERENCE_MISSING = '405';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_REFERENCE_MISSING = 'Customer reference is missing.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PASSWORDS_DONT_MATCH = '406';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_PASSWORDS_DONT_MATCH = 'Value in field %s should be identical to value in the %s field.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PASSWORD_CHANGE_FAILED = '407';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_PASSWORD_CHANGE_FAILED = 'Failed to change password.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_INVALID_PASSWORD = '408';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_INVALID_PASSWORD = 'Invalid password';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE = '409';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE = 'Failed to save customer address.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_FAILED_TO_SAVE = '410';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_FAILED_TO_SAVE = 'Failed to save customer.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_UNAUTHORIZED = '411';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED = 'Unauthorized request.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_ADDRESS_UUID_MISSING = '412';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_ADDRESS_UUID_MISSING = 'Address UUID is missing.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_NOT_ACCEPTED_TERMS = '413';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_NOT_ACCEPTED_TERMS = 'Terms and Conditions was not accepted.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_NOT_VALID_GENDER = '414';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_NOT_VALID_GENDER = 'Gender is not valid.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_RESTORE_PASSWORD_KEY_INVALID = '415';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_RESTORE_PASSWORD_KEY_INVALID = 'Restore password key is not valid.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_EMAIL_INVALID = '416';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_EMAIL_INVALID = 'Invalid Email address format.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_EMAIL_LENGTH_EXCEEDED = '417';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_EMAIL_LENGTH_EXCEEDED = 'Email is too long. It should have 100 characters or less.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_PASSWORD_TOO_SHORT = '418';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_PASSWORD_TOO_SHORT = 'The password is too short.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_PASSWORD_TOO_LONG = '419';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_PASSWORD_TOO_LONG = 'The password is too long.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_PASSWORD_INVALID_CHARACTER_SET = '420';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_PASSWORD_INVALID_CHARACTER_SET = 'The password character set is invalid.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_PASSWORD_SEQUENCE_NOT_ALLOWED = '421';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_PASSWORD_SEQUENCE_NOT_ALLOWED = 'The password contains sequence of the same character.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CUSTOMER_PASSWORD_DENY_LIST = '422';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CUSTOMER_PASSWORD_DENY_LIST = 'The password is listed as common.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONFIRMATION_CODE_INVALID = '423';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CONFIRMATION_CODE_INVALID = 'This email confirmation code is invalid or has been already used.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONFIRMATION_CODE_MISSING = '424';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CONFIRMATION_CODE_MISSING = 'Token is invalid.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONFIRMATION_FAILED = '425';

    /**
     * @var string
     */
    public const RESPONSE_MESSAGE_CONFIRMATION_FAILED = 'Failed to confirm a customer.';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_KEY_CONFIRM_EMAIL_LINK_INVALID_OR_USEDFIRM_EMAIL_LINK_INVALID_OR_USED
     *
     * @var string
     */
    protected const ERROR_CUSTOMER_CONFIRMATION_CODE_INVALID_OR_USED = 'customer.error.confirm_email_link.invalid_or_used';

    /**
     * Specification:
     * - Returns a mapping of possible customer module errors to Glue errors.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorMapping(): array
    {
        return [
            static::ERROR_CUSTOMER_CONFIRMATION_CODE_INVALID_OR_USED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CONFIRMATION_CODE_INVALID,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_MESSAGE_CONFIRMATION_CODE_INVALID,
            ],
        ];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const FORMAT_SELF_LINK_ADDRESS_RESOURCE = '%s/%s/%s/%s';
}
