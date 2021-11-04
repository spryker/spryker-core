<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Map\SpyMerchantRelationshipSalesOrderThresholdTableMap;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;

class MerchantRelationshipSalesOrderThresholdHelper extends Module
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function cleanupMerchantRelationshipSalesOrderThresholds(): void
    {
        $this->debug(sprintf(
            'Deleting All rows of table `%s`.',
            SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME,
        ));

        $this->getMerchantRelationshipSalesOrderThresholdQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMerchantRelationshipSalesOrderThresholdTableIsEmtpy(): void
    {
        $this->assertFalse($this->getMerchantRelationshipSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME));
    }

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
