<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability;

use Spryker\Service\Availability\FloatRounder\FloatRounder;
use Spryker\Service\Availability\FloatRounder\FloatRounderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Availability\AvailabilityConfig getConfig()
 */
class AvailabilityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Availability\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
