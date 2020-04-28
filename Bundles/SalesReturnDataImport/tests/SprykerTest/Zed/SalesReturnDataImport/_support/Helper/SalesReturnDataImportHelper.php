<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SalesReturnDataImport\Helper;

use Codeception\Module;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;

class SalesReturnDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertSalesReturnReasonDatabaseTablesContainsData(): void
    {
        $salesReturnReasonQuery = $this->getSalesReturnReasonQuery();

        $this->assertTrue(
            $salesReturnReasonQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @module SalesReturn
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function getSalesReturnReasonQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }
}
