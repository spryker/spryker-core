<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductPackagingUnit;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductPackagingUnit\Rounder\FloatRounder;
use Spryker\Service\ProductPackagingUnit\Rounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductPackagingUnit\Rounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
