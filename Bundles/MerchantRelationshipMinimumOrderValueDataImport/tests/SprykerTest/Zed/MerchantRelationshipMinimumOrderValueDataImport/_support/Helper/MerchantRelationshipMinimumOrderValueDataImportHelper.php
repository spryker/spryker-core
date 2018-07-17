<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValueDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Map\SpyMerchantRelationshipMinimumOrderValueTableMap;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery;

class MerchantRelationshipMinimumOrderValueDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function assertMerchantRelationshipMinimumOrderValueTableHasRecords(): void
    {
        $this->assertTrue($this->getMerchantRelationshipMinimumOrderValueQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyMerchantRelationshipMinimumOrderValueTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery
     */
    protected function getMerchantRelationshipMinimumOrderValueQuery(): SpyMerchantRelationshipMinimumOrderValueQuery
    {
        return SpyMerchantRelationshipMinimumOrderValueQuery::create();
    }
}
