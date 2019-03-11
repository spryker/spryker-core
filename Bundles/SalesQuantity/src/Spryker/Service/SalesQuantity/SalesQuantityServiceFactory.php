<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\SalesQuantity\FloatRounder\FloatRounder;
use Spryker\Service\SalesQuantity\FloatRounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\SalesQuantity\SalesQuantityConfig getConfig()
 */
class SalesQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\SalesQuantity\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
