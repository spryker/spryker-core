<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheckRestApi;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class HealthCheckRestApiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\HealthCheck\HealthCheckConfig::STORAGE_SERVICE_NAME
     */
    protected const STORAGE_SERVICE_NAME = 'storage';

    /**
     * @uses \Spryker\Shared\HealthCheck\HealthCheckConfig::SEARCH_SERVICE_NAME
     */
    public const SEARCH_SERVICE_NAME = 'search';

    /**
     * @uses \Spryker\Shared\HealthCheck\HealthCheckConfig::ZED_REQUEST_SERVICE_NAME
     */
    public const ZED_REQUEST_SERVICE_NAME = 'zed-request';

    /**
     * @return string[]
     */
    public function getWhiteListServiceNames(): array
    {
        return [
            static::STORAGE_SERVICE_NAME,
            static::SEARCH_SERVICE_NAME,
            static::ZED_REQUEST_SERVICE_NAME,
        ];
    }
}
