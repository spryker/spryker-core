<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;

class ProductListDataImportHelper extends Module
{
    /**
     * @var array
     */
    protected $productListMockData = [
        [
            'key' => 'test-01',
            'type' => 'whitelist',
            'title' => 'Test Product White List',
        ],
        [
            'key' => 'test-02',
            'type' => 'blacklist',
            'title' => 'Test Product Black List',
        ],
    ];

    /**
     * @return void
     */
    public function haveProductLists(): void
    {
        foreach ($this->productListMockData as $productListData) {
            $productListEntity = SpyProductListQuery::create()
                ->filterByKey($productListData['key'])
                ->findOneOrCreate();
            $productListEntity->setKey($productListData['key'])
                ->setTitle($productListData['title'])
                ->setType($productListData['type'])
                ->save();
        }
    }

    /**
     * @return void
     */
    public function ensureProductListTableIsEmpty(): void
    {
        $this->createProductListQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureProductListCategoryTableIsEmpty(): void
    {
        $this->createProductListCategoryQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureProductListProductConcreteTableIsEmpty(): void
    {
        $this->createProductListProductConcreteQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductListTableContainsRecords(): void
    {
        $query = $this->createProductListQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function assertProductListCategoryTableContainsRecords(): void
    {
        $query = $this->createProductListCategoryQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function assertProductListConcreteProductTableContainsRecords(): void
    {
        $query = $this->createProductListProductConcreteQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected function createProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery
     */
    protected function createProductListCategoryQuery(): SpyProductListCategoryQuery
    {
        return SpyProductListCategoryQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery
     */
    protected function createProductListProductConcreteQuery(): SpyProductListProductConcreteQuery
    {
        return SpyProductListProductConcreteQuery::create();
    }
}
