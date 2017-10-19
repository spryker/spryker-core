<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Newsletter;

interface NewsletterConstants
{
    const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME = 'DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME';
    const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_SUBJECT = 'DOUBLE_OPT_IN_CONFIRMATION_SUBJECT';
    const MERGE_LANGUAGE_HANDLEBARS = 'MERGE_LANGUAGE_HANDLEBARS';

    /** @deprecated Please use NewsletterConstants::BASE_URL_YVES instead */
    const HOST_YVES = 'HOST_YVES';

    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     */
    const BASE_URL_YVES = 'NEWSLETTER:BASE_URL_YVES';
}
