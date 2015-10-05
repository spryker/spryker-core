<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Mail;

use SprykerFeature\Shared\Library\ConfigInterface;

interface MailConfig extends ConfigInterface
{

    const MAILCATCHER_GUI = 'MAILCATCHER_GUI';
    const MAIL_PROVIDER_MANDRILL = 'mandrill';

    const MERGE_LANGUAGE_MAILCHIMP = 'mailchimp';
    const MERGE_LANGUAGE_HANDLEBARS = 'handlebars';

}
