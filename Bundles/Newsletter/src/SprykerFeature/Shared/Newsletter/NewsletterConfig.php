<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Newsletter;

use SprykerFeature\Shared\Library\ConfigInterface;

interface NewsletterConfig extends ConfigInterface
{

    const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME = 'DOUBLE_OPT_IN_CONFIRMATION_TEMPLATE_NAME';
    const SHOP_MAIL_DOUBLE_OPT_IN_CONFIRMATION_SUBJECT = 'DOUBLE_OPT_IN_CONFIRMATION_SUBJECT';

}
