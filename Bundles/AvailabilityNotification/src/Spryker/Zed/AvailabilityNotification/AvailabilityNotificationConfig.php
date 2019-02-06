<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AvailabilityNotificationConfig extends AbstractBundleConfig
{
    protected const AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI = '/%s/availability-notification/unsubscribe-by-key/%s';

    /**
     * @return string
     */
    public function getBaseUrlYves(): string
    {
        $config = $this->getConfig();

        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            return $config->get(ApplicationConstants::BASE_URL_YVES);
        }

        // @deprecated This is just for backward compatibility
        if ($config->hasKey(ApplicationConstants::HOST_YVES)) {
            return $config->get(ApplicationConstants::HOST_YVES);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getUnsubscribeUri(): string
    {
        return static::AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI;
    }
}
