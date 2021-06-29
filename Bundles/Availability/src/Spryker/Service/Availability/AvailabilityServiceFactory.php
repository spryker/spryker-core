<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability;

use Spryker\Service\Availability\Checker\StockChecker;
use Spryker\Service\Availability\Checker\StockCheckerInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\Availability\AvailabilityConfig getConfig()
 */
class AvailabilityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Availability\Checker\StockCheckerInterface
     */
    public function createStockChecker(): StockCheckerInterface
    {
        return new StockChecker($this->getConfig());
    }
}
