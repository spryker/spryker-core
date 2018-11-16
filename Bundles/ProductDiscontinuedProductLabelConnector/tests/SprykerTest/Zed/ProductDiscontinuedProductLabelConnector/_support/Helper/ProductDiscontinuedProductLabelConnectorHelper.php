<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedProductLabelConnector\Helper;

use Codeception\Module;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;

class ProductDiscontinuedProductLabelConnectorHelper extends Module
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
    public function ensureTableProductDiscontinuedIsEmpty(): void
    {
        $query = $this->getProductDiscontinuedQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureTableProductDiscontinuedNoteIsEmpty(): void
    {
        $query = $this->getProductDiscontinuedNoteQuery();
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
