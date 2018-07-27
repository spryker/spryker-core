<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MinimumOrderValueDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MinimumOrderValue\Persistence\Map\SpyMinimumOrderValueTableMap;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueQuery;

class MinimumOrderValueDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function assertMinimumOrderValueTableHasRecords(): void
    {
        $this->assertTrue($this->getMinimumOrderValueQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyMinimumOrderValueTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueQuery
     */
    protected function getMinimumOrderValueQuery(): SpyMinimumOrderValueQuery
    {
        return SpyMinimumOrderValueQuery::create();
    }
}
