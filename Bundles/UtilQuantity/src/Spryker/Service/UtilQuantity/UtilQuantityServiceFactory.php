<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilQuantity\Rounder\QuantityRounder;
use Spryker\Service\UtilQuantity\Rounder\QuantityRounderInterface;

class UtilQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilQuantity\Rounder\QuantityRounderInterface
     */
    public function createQuantityRounder(): QuantityRounderInterface
    {
        return new QuantityRounder();
    }
}
