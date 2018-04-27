<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;

class ProductMeasurementBaseUnitDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureMeasurementBaseUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMeasurementBaseUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMeasurementBaseUnitContainsData(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery
     */
    protected function getProductMeasurementBaseUnitQuery(): SpyProductMeasurementBaseUnitQuery
    {
        return SpyProductMeasurementBaseUnitQuery::create();
    }
}
