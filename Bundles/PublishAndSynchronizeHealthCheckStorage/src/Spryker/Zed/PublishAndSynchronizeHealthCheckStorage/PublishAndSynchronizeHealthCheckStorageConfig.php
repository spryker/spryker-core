<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PublishAndSynchronizeHealthCheckStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a string that is used in DateInterval to validate that the storage data is not older than this threshold.
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
