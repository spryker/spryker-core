<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySupplierDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery;

class CompanySupplierDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->getCompanySupplierToProductQuery()->find()->delete();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companySupplierToProductQuery = $this->getCompanySupplierToProductQuery();
        $this->assertTrue(($companySupplierToProductQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery
     */
    protected function getCompanySupplierToProductQuery(): SpyCompanySupplierToProductQuery
    {
        return SpyCompanySupplierToProductQuery::create();
    }
}
