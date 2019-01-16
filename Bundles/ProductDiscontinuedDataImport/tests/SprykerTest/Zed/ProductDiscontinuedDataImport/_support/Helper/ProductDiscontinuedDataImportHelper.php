<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductDiscontinuedDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductDiscontinuedDataImportHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->deleteProductDiscountedData();

        $this->getDataCleanupHelper()->_addCleanup(function () {
            $this->deleteProductDiscountedData();
        });
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

    /**
     * @return void
     */
    protected function deleteProductDiscountedData(): void
    {
        $this->getProductDiscontinuedNoteQuery()
            ->deleteAll();
        $this->getProductDiscontinuedQuery()
            ->deleteAll();
    }
}
