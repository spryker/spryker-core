<?php

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector\Helper;

use Codeception\Module;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;

class ProductAlternativeProductLabelConnectorHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductLabelProductAbstractQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductLabelProductAbstractQuery();
        $this->assertGreaterThan(
            0,
            $query->count(),
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    protected function getProductLabelProductAbstractQuery(): SpyProductLabelProductAbstractQuery
    {
        return SpyProductLabelProductAbstractQuery::create();
    }
}
