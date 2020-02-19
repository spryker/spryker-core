<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;

class MerchantOmsDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertMerchantOmsDatabaseTablesContainsData(): void
    {
        $merchantOmsProcessQuery = $this->getMerchantOmsProcessPropelQuery();

        $this->assertTrue(
            $merchantOmsProcessQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    protected function getMerchantOmsProcessPropelQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }
}
