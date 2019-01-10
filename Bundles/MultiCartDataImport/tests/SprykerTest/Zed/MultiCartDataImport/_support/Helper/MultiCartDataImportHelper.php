<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MultiCartDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;

class MultiCartDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertDatabaseTablesContainsData(): void
    {
        $quoteQuery = $this->getQuoteQuery();
        $this->assertTrue($quoteQuery->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function getQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }
}
