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
        $query = $this->getProductOfferPropelQuery();

        $this->assertSame(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductOfferPropelQuery();

        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
