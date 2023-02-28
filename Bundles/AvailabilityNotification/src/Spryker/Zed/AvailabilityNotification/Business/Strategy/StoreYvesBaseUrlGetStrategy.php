<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Strategy;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;

class StoreYvesBaseUrlGetStrategy implements BaseUrlGetStrategyInterface
{
    /**
     * @var int
     */
    protected const PORT_HTTPS = 443;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected AvailabilityNotificationConfig $availabilityNotificationConfig;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $availabilityNotificationConfig
     */
    public function __construct(AvailabilityNotificationConfig $availabilityNotificationConfig)
    {
        $this->availabilityNotificationConfig = $availabilityNotificationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isApplicable(?StoreTransfer $storeTransfer = null): bool
    {
        if (!$storeTransfer || !$storeTransfer->getName()) {
            return false;
        }

        return isset($this->availabilityNotificationConfig->getStoreToYvesHostMapping()[$storeTransfer->getNameOrFail()]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    public function getBaseUrl(?StoreTransfer $storeTransfer = null): string
    {
        if (!$storeTransfer) {
            return '';
        }

        $yvesHost = $this->availabilityNotificationConfig->getStoreToYvesHostMapping()[$storeTransfer->getNameOrFail()];

        return sprintf(
            '%s://%s',
            $this->availabilityNotificationConfig->getBaseUrlYvesPort() === static::PORT_HTTPS ? 'https' : 'http',
            $yvesHost,
        );
    }
}
