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
    protected const ROUTE_UNSUBSCRIBE = '/availability-notification/unsubscribe-by-key/';

    /**
     * @return string|null
     */
    public function getBaseUrlYves(): ?string
    {
        $config = $this->getConfig();

        if ($config->hasKey(ApplicationConstants::BASE_URL_YVES)) {
            return $config->get(ApplicationConstants::BASE_URL_YVES);
        }

        // @deprecated This is just for backward compatibility
        if ($config->hasKey(ApplicationConstants::HOST_YVES)) {
            return $config->get(ApplicationConstants::HOST_YVES);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getUnsubscribeRoute(): string
    {
        return static::ROUTE_UNSUBSCRIBE;
    }
}
