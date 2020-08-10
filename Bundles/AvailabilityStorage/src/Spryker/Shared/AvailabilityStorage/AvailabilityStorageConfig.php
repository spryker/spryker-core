<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AvailabilityStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class AvailabilityStorageConfig extends AbstractBundleConfig
{
    /**
     * Defines queue name that as used for asynchronous event handling.
     */
    public const PUBLISH_AVAILABILITY = 'publish.availability';
}
