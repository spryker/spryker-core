<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValue\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Map\SpyMerchantRelationshipMinimumOrderValueTableMap;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery;

class MerchantRelationshipMinimumOrderValueHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function cleanupMerchantRelationshipMinimumOrderValues(): void
    {
        $this->debug(sprintf(
            'Deleting All rows of table `%s`.',
            SpyMerchantRelationshipMinimumOrderValueTableMap::TABLE_NAME
        ));

        $this->getMerchantRelationshipMinimumOrderValueQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMerchantRelationshipMinimumOrderValueTableIsEmtpy(): void
    {
        $this->assertFalse($this->getMerchantRelationshipMinimumOrderValueQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyMerchantRelationshipMinimumOrderValueTableMap::TABLE_NAME));
    }

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
