<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

use Spryker\Service\Discount\FloatRounder\FloatRounder;
use Spryker\Service\Discount\FloatRounder\FloatRounderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Discount\DiscountConfig getConfig()
 */
class DiscountServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Discount\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
