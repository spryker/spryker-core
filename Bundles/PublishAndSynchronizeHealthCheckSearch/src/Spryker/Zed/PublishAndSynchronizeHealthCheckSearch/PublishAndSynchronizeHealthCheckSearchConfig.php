<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PublishAndSynchronizeHealthCheckSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - The name of the source inside the search.
     *
     * @api
     * @var string
     */
    public const SOURCE_IDENTIFIER = 'page';

    /**
     * Specification:
     * - Returns a string that can is used in DateInterval to validate that the search data is not older then this threshold.
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
