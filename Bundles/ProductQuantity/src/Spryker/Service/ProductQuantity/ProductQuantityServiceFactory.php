<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductQuantity\FloatRounder\FloatRounder;
use Spryker\Service\ProductQuantity\FloatRounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\ProductQuantity\ProductQuantityConfig getConfig()
 */
class ProductQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductQuantity\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
