<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PublishAndSynchronizeHealthCheckConfig extends AbstractBundleConfig
{
    public const DEFAULT_HEALTH_CHECK_KEY = 'health-check';

    /**
     * Specification:
     * - Returns a string that can is used in DateInterval to validate that the update_at field is not older then this threshold.
     *
     * @api
     *
     * @return string
     */
    public static function getValidationThreshold(): string
    {
        return '5 minutes';
    }
}
