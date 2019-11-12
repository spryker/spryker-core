<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\HealthCheckRestApi\HealthCheckRestApiConstants;

class HealthCheckRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_HEALTH_CHECK = 'health-check';
    public const CONTROLLER_HEALTH_CHECK = 'health-check-resource';

    public const RESPONSE_CODE_SERVICES_ARE_NOT_ACCESSIBLE = '9901';
    public const RESPONSE_DETAILS_CONTENT_NOT_FOUND = 'Services are not accessible.';

    public const RESPONSE_CODE_SERVICES_ARE_DISABLED = '9903';
    public const RESPONSE_DETAILS_SERVICES_ARE_DISABLED = 'Services are disabled.';

    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckRestApiConstants::HEALTH_CHECK_ENABLED, true);
    }
}
