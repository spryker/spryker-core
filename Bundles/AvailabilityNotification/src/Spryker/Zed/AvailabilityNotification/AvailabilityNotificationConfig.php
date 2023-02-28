<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AvailabilityNotificationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI = '/%s/availability-notification/unsubscribe-by-key/%s';

    /**
     * @var bool
     */
    protected const AVAILABILITY_NOTIFICATION_CHECK_PRODUCT_EXISTS = false;

    /**
     * @var int
     */
    protected const DEFAULT_BASE_URL_YVES_PORT = 443;

    /**
     * @api
     *
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
     * @api
     *
     * @return string
     */
    public function getUnsubscribeUri(): string
    {
        return static::AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function availabilityNotificationCheckProductExists(): bool
    {
        return static::AVAILABILITY_NOTIFICATION_CHECK_PRODUCT_EXISTS;
    }

    /**
     * Specification:
     * - Returns stores to Yves host mapping.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getStoreToYvesHostMapping(): array
    {
        return $this->get(AvailabilityNotificationConstants::STORE_TO_YVES_HOST_MAPPING, []);
    }

    /**
     * Specification:
     * - Returns base URL Yves port.
     *
     * @api
     *
     * @return int
     */
    public function getBaseUrlYvesPort(): int
    {
        return $this->get(AvailabilityNotificationConstants::BASE_URL_YVES_PORT, static::DEFAULT_BASE_URL_YVES_PORT);
    }
}
