<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStoreQuery;

class ProductMeasurementSalesUnitStoreDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureMeasurementSalesUnitStoreIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitStoreIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitStoreContainsData(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStoreQuery
     */
    protected function getProductMeasurementSalesUnitStoreQuery(): SpyProductMeasurementSalesUnitStoreQuery
    {
        return SpyProductMeasurementSalesUnitStoreQuery::create();
    }
}
