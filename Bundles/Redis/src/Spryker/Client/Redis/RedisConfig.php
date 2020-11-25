<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Spryker\Client\Kernel\AbstractBundleConfig;

class RedisConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isDevelopmentMode(): bool
    {
        return APPLICATION_ENV === 'development' || APPLICATION_ENV === 'docker.dev';
    }
}
