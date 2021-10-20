<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Customer\Code;

interface Messages
{
    /**
     * @var string
     */
    public const CUSTOMER_ALREADY_AUTHENTICATED = 'customer.already.authenticated';

    /**
     * @var string
     */
    public const CUSTOMER_REGISTRATION_SUCCESS = 'customer.registration.success';

    /**
     * @var string
     */
    public const CUSTOMER_REGISTRATION_CONFIRMED = 'customer.registration.confirmed';

    /**
     * @var string
     */
    public const CUSTOMER_REGISTRATION_TIMEOUT = 'customer.registration.timeout';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_UNKNOWN = 'customer.address.unknown';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_UPDATED = 'customer.address.updated';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_NOT_ADDED = 'customer.address.not.added';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_ADDED = 'customer.address.added';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_DELETE_SUCCESS = 'customer.address.delete.success';

    /**
     * @var string
     */
    public const CUSTOMER_ADDRESS_DELETE_FAILED = 'customer.address.delete.failed';

    /**
     * @var string
     */
    public const CUSTOMER_PASSWORD_RECOVERY_MAIL_SENT = 'customer.password.recovery.mail.sent';

    /**
     * @var string
     */
    public const CUSTOMER_PASSWORD_CHANGED = 'customer.password.changed';

    /**
     * @var string
     */
    public const CUSTOMER_DELETE_FAILED = 'customer.delete.failed';

    /**
     * @var string
     */
    public const CUSTOMER_AUTHORIZATION_SUCCESS = 'customer.authorization.success';

    /**
     * @var string
     */
    public const CUSTOMER_AUTHORIZATION_FAILED = 'customer.authorization.failed';

    /**
     * @var string
     */
    public const CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';

    /**
     * @var string
     */
    public const CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';

    /**
     * @var string
     */
    public const CUSTOMER_EMAIL_FORMAT_INVALID = 'customer.email.format.invalid';

    /**
     * @var string
     */
    public const CUSTOMER_EMAIL_INVALID = 'customer.email.invalid';

    /**
     * @var string
     */
    public const CUSTOMER_EMAIL_TOO_LONG = 'customer.email.length.exceeded';

    /**
     * @var string
     */
    public const CUSTOMER_TOKEN_INVALID = 'customer.token.invalid';

    /**
     * @var string
     */
    public const CUSTOMER_ANONYMIZATION_SUCCESS = 'customer.anonymization.success';

    /**
     * @var string
     */
    public const CUSTOMER_ANONYMIZATION_FAILED = 'customer.anonymization.failed';
}
