<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Helper;

use Codeception\Module;
use Orm\Zed\MinimumOrderValue\Persistence\Map\SpyMinimumOrderValueTypeTableMap;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueTypeQuery;

class MinimumOrderValueHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least %d entry in the database table `%s` but database` table is empty.';

    /**
     * @return void
     */
    public function truncateMinimumOrderValueTypes(): void
    {
        $this->getMinimumOrderValueTypeQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMinimumOrderValueTypeTableIsEmtpy(): void
    {
        $this->assertFalse($this->getMinimumOrderValueTypeQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyMinimumOrderValueTypeTableMap::TABLE_NAME));
    }

    /**
     * @param int $recordsNum
     *
     * @return void
     */
    public function assertMinimumOrderValueTypeTableHasRecords(int $recordsNum): void
    {
        $this->assertEquals($this->getMinimumOrderValueTypeQuery()->count(), $recordsNum, sprintf(static::ERROR_MESSAGE_EXPECTED, $recordsNum, SpyMinimumOrderValueTypeTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueTypeQuery
     */
    protected function getMinimumOrderValueTypeQuery(): SpyMinimumOrderValueTypeQuery
    {
        return SpyMinimumOrderValueTypeQuery::create();
    }
}
