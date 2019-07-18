<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Map\SpyMerchantRelationshipSalesOrderThresholdTableMap;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;

class MerchantRelationshipSalesOrderThresholdDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function assertMerchantRelationshipSalesOrderThresholdTableHasRecords(): void
    {
        $this->assertTrue($this->getMerchantRelationshipSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    protected function getMerchantRelationshipSalesOrderThresholdQuery(): SpyMerchantRelationshipSalesOrderThresholdQuery
    {
        return SpyMerchantRelationshipSalesOrderThresholdQuery::create();
    }
}
