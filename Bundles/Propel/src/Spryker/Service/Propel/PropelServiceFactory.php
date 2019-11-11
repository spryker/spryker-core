<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Propel;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Propel\HealthIndicator\HealthIndicator;
use Spryker\Service\Propel\HealthIndicator\HealthIndicatorInterface;

class PropelServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Propel\HealthIndicator\HealthIndicatorInterface
     */
    public function createStorageHealthIndicator(): HealthIndicatorInterface
    {
        return new HealthIndicator();
    }
}
