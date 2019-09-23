<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;

interface PriceProductSchedulePersistenceFactoryInterface
{
    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    public function createPriceProductScheduleQuery(): SpyPriceProductScheduleQuery;
}
