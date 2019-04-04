<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductSchedule\Business\Model\PriceProductScheduleCleaner;
use Spryker\Zed\PriceProductSchedule\Business\Model\PriceProductScheduleCleanerInterface;

/**
 * @method \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig getConfig()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface getEntityManager()
 */
class PriceProductScheduleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductSchedule\Business\Model\PriceProductScheduleCleanerInterface
     */
    public function createPriceProductScheduleCleaner(): PriceProductScheduleCleanerInterface
    {
        return new PriceProductScheduleCleaner(
            $this->getEntityManager()
        );
    }
}
