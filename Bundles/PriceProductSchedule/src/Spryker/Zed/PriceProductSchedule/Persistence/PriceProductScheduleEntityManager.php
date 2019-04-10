<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
class PriceProductScheduleEntityManager extends AbstractEntityManager implements PriceProductScheduleEntityManagerInterface
{
    protected const PATTERN_MINUS_DAYS = '-%s days';

    /**
     * @param int $daysRetained
     *
     * @return void
     */
    public function deleteOldScheduledPrices(int $daysRetained): void
    {
        $priceProductScheduleQuery = $this->getFactory()
            ->createPriceProductScheduleQuery();

        $filterTo = (new DateTime(sprintf(static::PATTERN_MINUS_DAYS, $daysRetained)));

        $priceProductScheduleQuery
            ->filterByActiveTo(['max' => $filterTo], Criteria::LESS_THAN)
            ->filterByIsCurrent(false)
            ->delete();
    }
}
