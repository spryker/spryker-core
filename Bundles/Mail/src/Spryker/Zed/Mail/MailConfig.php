<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MailConfig extends AbstractBundleConfig
{
    public const MAIL_TYPE_ALL = '*';

    /**
     * @return string
     */
    public function getSenderName()
    {
        return 'mail.sender.name';
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return 'mail.sender.email';
    }
}
