<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Customer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CustomerConstants
{
    /**
     * @var string
     */
    public const CUSTOMER_ANONYMOUS_PATTERN = 'CUSTOMER_ANONYMOUS_PATTERN';

    /**
     * @var string
     */
    public const CUSTOMER_SECURED_PATTERN = 'CUSTOMER_SECURED_PATTERN';

    /**
     * Specification:
     * - Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     * @var string
     */
    public const BASE_URL_YVES = 'CUSTOMER:BASE_URL_YVES';

    /**
     * @var string
     */
    public const NAME_CUSTOMER_REFERENCE = 'CustomerReference';

    /**
     * @var string
     */
    public const PARAM_ID_CUSTOMER = 'id-customer';

    /**
     * @var string
     */
    public const PARAM_ID_CUSTOMER_ADDRESS = 'id-customer-address';

    /**
     * @var string
     */
    public const SHOP_MAIL_FROM_EMAIL_NAME = 'SHOP_MAIL_FROM_EMAIL_NAME';

    /**
     * @var string
     */
    public const SHOP_MAIL_FROM_EMAIL_ADDRESS = 'SHOP_MAIL_FROM_EMAIL_ADDRESS';

    /**
     * @var string
     */
    public const SHOP_MAIL_REGISTRATION_TOKEN = 'SHOP_MAIL_REGISTRATION_TOKEN';

    /**
     * @var string
     */
    public const SHOP_MAIL_REGISTRATION_SUBJECT = 'SHOP_MAIL_REGISTRATION_SUBJECT';

    /**
     * @var string
     */
    public const SHOP_MAIL_PASSWORD_RESTORE_TOKEN = 'PASSWORD_RESTORE_TOKEN';

    /**
     * @var string
     */
    public const SHOP_MAIL_PASSWORD_RESTORE_SUBJECT = 'PASSWORD_RESTORE_SUBJECT';

    /**
     * @var string
     */
    public const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_TOKEN = 'PASSWORD_RESTORED_CONFIRMATION_TOKEN';

    /**
     * @var string
     */
    public const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_SUBJECT = 'PASSWORD_RESTORED_CONFIRMATION_SUBJECT';

    /**
     * Specification:
     * - Provides format of registration confirmation token url.
     * - Should contain %s for the token.
     *
     * @var string
     */
    public const REGISTRATION_CONFIRMATION_TOKEN_URL = 'CUSTOMER:REGISTRATION_CONFIRMATION_TOKEN_URL';
}
