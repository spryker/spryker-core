<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;

class MerchantOmsDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertMerchantOmsDatabaseTablesContainsData(): void
    {
        $merchantOmsProcessQuery = $this->getMerchantOmsProcessPropelQuery();

        $this->assertTrue(
            $merchantOmsProcessQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    protected function getMerchantOmsProcessPropelQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }
}
