<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Customer\Code;

interface Messages
{

    const CUSTOMER_ALREADY_AUTHENTICATED = 'customer.already.authenticated';
    const CUSTOMER_REGISTRATION_SUCCESS = 'customer.registration.success';
    const CUSTOMER_REGISTRATION_CONFIRMED = 'customer.registration.confirmed';
    const CUSTOMER_REGISTRATION_TIMEOUT = 'customer.registration.timeout';
    const CUSTOMER_ADDRESS_UNKNOWN = 'customer.address.unknown';
    const CUSTOMER_ADDRESS_UPDATED = 'customer.address.updated';
    const CUSTOMER_ADDRESS_NOT_ADDED = 'customer.address.not.added';
    const CUSTOMER_ADDRESS_ADDED = 'customer.address.added';
    const CUSTOMER_ADDRESS_DELETE_SUCCESS = 'customer.address.delete.success';
    const CUSTOMER_ADDRESS_DELETE_FAILED = 'customer.address.delete.failed';
    const CUSTOMER_PASSWORD_RECOVERY_MAIL_SENT = 'customer.password.recovery.mail.sent';
    const CUSTOMER_DELETE_FAILED = 'customer.delete.failed';
    const CUSTOMER_AUTHORIZATION_SUCCESS = 'customer.authorization.success';
    const CUSTOMER_AUTHORIZATION_FAILED = 'customer.authorization.failed';
    const CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';
    const CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';
    const CUSTOMER_EMAIL_INVALID = 'customer.email.invalid';
    const CUSTOMER_TOKEN_INVALID = 'customer.token.invalid';
}
