<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SalesOrderThresholdDataImport\Helper;

use Codeception\Module;
use Orm\Zed\SalesOrderThreshold\Persistence\Map\SpySalesOrderThresholdTableMap;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdQuery;

class SalesOrderThresholdDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function assertSalesOrderThresholdTableHasRecords(): void
    {
        $this->assertTrue($this->getSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpySalesOrderThresholdTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdQuery
     */
    protected function getSalesOrderThresholdQuery(): SpySalesOrderThresholdQuery
    {
        return SpySalesOrderThresholdQuery::create();
    }
}
