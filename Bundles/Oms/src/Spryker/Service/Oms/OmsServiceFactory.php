<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oms;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Oms\Rounder\FloatRounder;
use Spryker\Service\Oms\Rounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\Oms\OmsConfig getConfig()
 */
class OmsServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Oms\Rounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
