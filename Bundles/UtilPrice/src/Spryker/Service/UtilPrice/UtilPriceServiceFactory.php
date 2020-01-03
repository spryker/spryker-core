<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilPrice;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilPrice\Rounder\PriceRounder;
use Spryker\Service\UtilPrice\Rounder\PriceRounderInterface;

class UtilPriceServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilPrice\Rounder\PriceRounderInterface
     */
    public function createPriceRounder(): PriceRounderInterface
    {
        return new PriceRounder();
    }
}
