<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Customer;

interface CustomerConstants
{

    const CUSTOMER_ANONYMOUS_PATTERN = 'CUSTOMER_ANONYMOUS_PATTERN';
    const CUSTOMER_SECURED_PATTERN = 'CUSTOMER_SECURED_PATTERN';

    /** @deprecated Use CustomerConstants::BASE_URL_YVES instead */
    const HOST_YVES = 'HOST_YVES';

    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     */
    const BASE_URL_YVES = 'CUSTOMER:BASE_URL_YVES';

    const NAME_CUSTOMER_REFERENCE = 'CustomerReference';

    const PARAM_ID_CUSTOMER = 'id-customer';
    const PARAM_ID_CUSTOMER_ADDRESS = 'id-customer-address';

    const SHOP_MAIL_FROM_EMAIL_NAME = 'SHOP_MAIL_FROM_EMAIL_NAME';
    const SHOP_MAIL_FROM_EMAIL_ADDRESS = 'SHOP_MAIL_FROM_EMAIL_ADDRESS';
    const SHOP_MAIL_REGISTRATION_TOKEN = 'SHOP_MAIL_REGISTRATION_TOKEN';
    const SHOP_MAIL_REGISTRATION_SUBJECT = 'SHOP_MAIL_REGISTRATION_SUBJECT';
    const SHOP_MAIL_PASSWORD_RESTORE_TOKEN = 'PASSWORD_RESTORE_TOKEN';
    const SHOP_MAIL_PASSWORD_RESTORE_SUBJECT = 'PASSWORD_RESTORE_SUBJECT';
    const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_TOKEN = 'PASSWORD_RESTORED_CONFIRMATION_TOKEN';
    const SHOP_MAIL_PASSWORD_RESTORED_CONFIRMATION_SUBJECT = 'PASSWORD_RESTORED_CONFIRMATION_SUBJECT';

}
