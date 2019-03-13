<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilProduct;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilProduct\Rounder\PriceRounder;
use Spryker\Service\UtilProduct\Rounder\PriceRounderInterface;
use Spryker\Service\UtilProduct\Rounder\QuantityRounder;
use Spryker\Service\UtilProduct\Rounder\QuantityRounderInterface;

class UtilProductServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilProduct\Rounder\PriceRounderInterface
     */
    public function createPriceRounder(): PriceRounderInterface
    {
        return new PriceRounder();
    }

    /**
     * @return \Spryker\Service\UtilProduct\Rounder\QuantityRounderInterface
     */
    public function createQuantityRounder(): QuantityRounderInterface
    {
        return new QuantityRounder();
    }
}
