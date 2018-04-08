<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySupplierDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanyTypeQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class CompanySupplierDataImportHelper extends Module
{
    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isProductCreated(string $sku): bool
    {
        $productQuery = SpyProductQuery::create();
        $productQuery->filterBySku($sku);

        return $productQuery->exists();
    }

    /**
     * @return void
     */
    public function ensureDatabaseTableCompanySupplierToProductIsEmpty(): void
    {
        $this->getCompanySupplierToProductQuery()->find()->delete();
    }

    /**
     * @return void
     */
    public function ensureDatabaseTableCompanyTypeIsEmpty(): void
    {
        $colPhpName = SpyCompanyTableMap::getTableMap()
            ->getColumn(SpyCompanyTableMap::COL_FK_COMPANY_TYPE)
            ->getPhpName();
        $this->getSpyCompanyQuery()->update([$colPhpName => null]);

        $this->getSpyCompanyTypeQuery()->find()->delete();
    }

    /**
     * @return void
     */
    public function ensureDatabaseTablePriceProductIsEmpty(): void
    {
        $priceProductQuery = $this->getSpyPriceProductQuery();
        $priceProductQuery->filterByFkCompany(null, Criteria::ISNOTNULL);
        foreach ($priceProductQuery->find() as $priceProduct) {
            $priceProduct->getPriceProductStores()->delete();
            $priceProduct->delete();
        }
    }

    /**
     * @return void
     */
    public function assertDatabaseTableCompanySupplierToProductContainsData(): void
    {
        $companySupplierToProductQuery = $this->getCompanySupplierToProductQuery();
        $this->assertTrue(($companySupplierToProductQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function assertCompanyTypeImported(): void
    {
        $companyQuery = $this->getSpyCompanyQuery();
        $companyQuery->filterByFkCompanyType(null, Criteria::ISNOTNULL);
        $this->assertTrue(($companyQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function assertCompanySupplierProductPriceImported(): void
    {
        $priceProductQuery = $this->getSpyPriceProductQuery();
        $priceProductQuery->filterByFkCompany(null, Criteria::ISNOTNULL);
        $this->assertTrue(($priceProductQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProductQuery
     */
    protected function getCompanySupplierToProductQuery(): SpyCompanySupplierToProductQuery
    {
        return SpyCompanySupplierToProductQuery::create();
    }

    /**
     * @return \Orm\Zed\CompanySupplier\Persistence\SpyCompanyTypeQuery
     */
    protected function getSpyCompanyTypeQuery(): SpyCompanyTypeQuery
    {
        return SpyCompanyTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function getSpyCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function getSpyPriceProductQuery(): SpyPriceProductQuery
    {
        return SpyPriceProductQuery::create();
    }
}
