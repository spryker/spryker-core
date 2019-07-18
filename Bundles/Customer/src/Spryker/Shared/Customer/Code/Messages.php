<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Customer\Code;

interface Messages
{
    public const CUSTOMER_ALREADY_AUTHENTICATED = 'customer.already.authenticated';
    public const CUSTOMER_REGISTRATION_SUCCESS = 'customer.registration.success';
    public const CUSTOMER_REGISTRATION_CONFIRMED = 'customer.registration.confirmed';
    public const CUSTOMER_REGISTRATION_TIMEOUT = 'customer.registration.timeout';
    public const CUSTOMER_ADDRESS_UNKNOWN = 'customer.address.unknown';
    public const CUSTOMER_ADDRESS_UPDATED = 'customer.address.updated';
    public const CUSTOMER_ADDRESS_NOT_ADDED = 'customer.address.not.added';
    public const CUSTOMER_ADDRESS_ADDED = 'customer.address.added';
    public const CUSTOMER_ADDRESS_DELETE_SUCCESS = 'customer.address.delete.success';
    public const CUSTOMER_ADDRESS_DELETE_FAILED = 'customer.address.delete.failed';
    public const CUSTOMER_PASSWORD_RECOVERY_MAIL_SENT = 'customer.password.recovery.mail.sent';
    public const CUSTOMER_PASSWORD_CHANGED = 'customer.password.changed';
    public const CUSTOMER_DELETE_FAILED = 'customer.delete.failed';
    public const CUSTOMER_AUTHORIZATION_SUCCESS = 'customer.authorization.success';
    public const CUSTOMER_AUTHORIZATION_FAILED = 'customer.authorization.failed';
    public const CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';
    public const CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';
    public const CUSTOMER_EMAIL_FORMAT_INVALID = 'customer.email.format.invalid';
    public const CUSTOMER_EMAIL_INVALID = 'customer.email.invalid';
    public const CUSTOMER_EMAIL_TOO_LONG = 'customer.email.length.exceeded';
    public const CUSTOMER_TOKEN_INVALID = 'customer.token.invalid';
    public const CUSTOMER_ANONYMIZATION_SUCCESS = 'customer.anonymization.success';
    public const CUSTOMER_ANONYMIZATION_FAILED = 'customer.anonymization.failed';
}
