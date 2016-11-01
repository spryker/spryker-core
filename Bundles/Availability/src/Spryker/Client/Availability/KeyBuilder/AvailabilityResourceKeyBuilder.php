<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\KeyBuilder;

use Spryker\Shared\Availability\AvailabilityConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

class AvailabilityResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return AvailabilityConstants::RESOURCE_TYPE_AVAILABILITY_ABSTRACT;
    }

}
