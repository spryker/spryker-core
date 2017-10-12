<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\KeyBuilder;

use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;

class AvailabilityResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return AvailabilityConfig::RESOURCE_TYPE_AVAILABILITY_ABSTRACT;
    }
}
