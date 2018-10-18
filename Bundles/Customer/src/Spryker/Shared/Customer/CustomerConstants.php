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
    public const CUSTOMER_ANONYMOUS_PATTERN = 'CUSTOMER_ANONYMOUS_PATTERN';
    public const CUSTOMER_SECURED_PATTERN = 'CUSTOMER_SECURED_PATTERN';

    /**
     * Specification:
     * - Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     */
    public const BASE_URL_YVES = 'CUSTOMER:BASE_URL_YVES';

    public const NAME_CUSTOMER_REFERENCE = 'CustomerReference';

    public const PARAM_ID_CUSTOMER = 'id-customer';
    public const PARAM_ID_CUSTOMER_ADDRESS = 'id-customer-address';

    public const SHOP_MAIL_FROM_EMAIL_NAME = 'SHOP_MAIL_FROM_EMAIL_NAME';
    public const SHOP_MAIL_FROM_EMAIL_ADDRESS = 'SHOP_MAIL_FROM_EMAIL_ADDRESS';
    public const SHOP_MAIL_REGISTRATION_TOKEN = 'SHOP_MAIL_REGISTRATION_TOKEN';
    public const SHOP_MAIL_REGISTRATION_SUBJECT = 'SHOP_MAIL_REGISTRATION_SUBJECT';
    public const SHOP_MAIL_PASSWORD_RESTORE_TOKEN = 'PASSWORD_RESTORE_TOKEN';
    public const SHOP_MAIL_PASSWORD_RESTORE_SUBJECT = 'PASSWORD_RESTORE_SUBJECT';
    public const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_TOKEN = 'PASSWORD_RESTORED_CONFIRMATION_TOKEN';
    public const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_SUBJECT = 'PASSWORD_RESTORED_CONFIRMATION_SUBJECT';
}
