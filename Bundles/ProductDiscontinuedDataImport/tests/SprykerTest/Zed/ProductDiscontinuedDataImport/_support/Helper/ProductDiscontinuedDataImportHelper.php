<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;

class ProductDiscontinuedDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->getProductDiscontinuedNoteQuery()
            ->deleteAll();
        $this->getProductDiscontinuedQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTablesContainsData(): void
    {
        $productDiscontinuedQuery = $this->getProductDiscontinuedQuery();
        $this->assertTrue(($productDiscontinuedQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');

        $productDiscontinuedNoteQuery = $this->getProductDiscontinuedNoteQuery();
        $this->assertTrue(($productDiscontinuedNoteQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery
     */
    protected function getProductDiscontinuedQuery(): SpyProductDiscontinuedQuery
    {
        return SpyProductDiscontinuedQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery
     */
    protected function getProductDiscontinuedNoteQuery(): SpyProductDiscontinuedNoteQuery
    {
        return SpyProductDiscontinuedNoteQuery::create();
    }
}
