<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\QuoteRequestDataImport\Helper;

use Codeception\Module;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery;

class QuoteRequestDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertQuoteRequestDatabaseTablesContainsData(): void
    {
        $quoteRequestQuery = $this->getQuoteRequestQuery();

        $this->assertTrue(
            $quoteRequestQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return void
     */
    public function assertQuoteRequestVersionDatabaseTablesContainsData(): void
    {
        $quoteRequestItemQuery = $this->getQuoteRequestVersionQuery();

        $this->assertTrue(
            $quoteRequestItemQuery->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function getQuoteRequestQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery
     */
    protected function getQuoteRequestVersionQuery(): SpyQuoteRequestVersionQuery
    {
        return SpyQuoteRequestVersionQuery::create();
    }
}
