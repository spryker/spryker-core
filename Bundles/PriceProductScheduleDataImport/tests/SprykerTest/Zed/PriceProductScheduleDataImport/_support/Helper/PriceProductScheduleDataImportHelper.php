<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleDataImport\Helper;

use Codeception\Module;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;

class PriceProductScheduleDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $priceProductScheduleQuery = $this->getPriceProductScheduleQuery();

        $priceProductScheduleQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $priceProductScheduleQuery = $this->getPriceProductScheduleQuery();

        $this->assertTrue(
            $priceProductScheduleQuery->exists(),
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return SpyPriceProductScheduleQuery::create();
    }
}
