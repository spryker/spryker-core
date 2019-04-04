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
    /**
     * @param int $daysRetained
     *
     * @return void
     */
    public function deleteAppliedScheduledPrices(int $daysRetained): void
    {
        $priceProductScheduleQuery = $this->getFactory()
            ->createPriceProductScheduleQuery();

        $filterTo = (new DateTime(sprintf('-%s days', $daysRetained)));

        $priceProductScheduleQuery
            ->filterByActiveTo(['max' => $filterTo], Criteria::LESS_THAN)
            ->delete();
    }
}
