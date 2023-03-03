<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Builder;

use Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\MessageSentReport;

interface MessageSentReportIdentifierBuilderInterface
{
    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\MessageSentReport $messageSentReport
     *
     * @return string
     */
    public function builderIdentifier(MessageSentReport $messageSentReport): string;
}
