<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PublishAndSynchronizeHealthCheck;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PublishAndSynchronizeHealthCheckConfig extends AbstractBundleConfig
{
    /**
     * Defines queue name for processing publish.
     *
     * @var string
     */
    public const PUBLISH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK = 'publish.publish_and_synchronize_health_check';

    /**
     * Defines resource name, that will be used for key generation.
     *
     * @var string
     */
    public const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_RESOURCE_NAME = 'publish_and_synchronize_health_check';
}
