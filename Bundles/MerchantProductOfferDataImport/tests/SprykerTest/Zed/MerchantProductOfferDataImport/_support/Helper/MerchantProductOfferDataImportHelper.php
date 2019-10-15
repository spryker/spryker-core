<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;

class MerchantProductOfferDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductOfferQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductOfferQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function truncateProductOffers(): void
    {
        $this->getProductOfferQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
