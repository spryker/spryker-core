<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Newsletter;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface NewsletterConstants
{
    public const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME = 'DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME';
    public const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_SUBJECT = 'DOUBLE_OPT_IN_CONFIRMATION_SUBJECT';
    public const MERGE_LANGUAGE_HANDLEBARS = 'MERGE_LANGUAGE_HANDLEBARS';

    /**
     * @deprecated Please use NewsletterConstants::BASE_URL_YVES instead
     */
    public const HOST_YVES = 'HOST_YVES';

    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     */
    public const BASE_URL_YVES = 'NEWSLETTER:BASE_URL_YVES';

    public const DEFAULT_NEWSLETTER_TYPE = 'DEFAULT_NEWSLETTER';
}
