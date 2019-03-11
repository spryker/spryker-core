<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilNumber\Rounder\FloatRounder;
use Spryker\Service\UtilNumber\Rounder\FloatRounderInterface;

class UtilNumberServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilNumber\Rounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder();
    }
}
