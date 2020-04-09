<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductRelationBuilder;
use Generated\Shared\DataBuilder\ProductRelationTypeBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Business
 * @group Facade
 * @group CreateProductRelationTest
 * Add your own group annotations below this line
 */
class CreateProductRelationTest extends Unit
{
    protected const FIRST_FIXTURE_VALUE = 'test';
    protected const SECOND_FIXTURE_VALUE = 'test1';

    /**
     * @var \SprykerTest\Zed\ProductRelation\ProductRelationBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productRelationFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testCreateProductRelationShouldCreateProductRelationWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureProductRelationTableIsEmpty();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $productRelationTypeTransfer = (new ProductRelationTypeBuilder())->seed([
            ProductRelationTypeTransfer::KEY => 'up-selling',
        ])->build();

        $productRelationTransfer = (new ProductRelationBuilder())->seed([
            ProductRelationTransfer::PRODUCT_RELATION_TYPE => $productRelationTypeTransfer,
            ProductRelationTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductRelationTransfer::PRODUCT_RELATION_KEY => static::SECOND_FIXTURE_VALUE,
            ProductRelationTransfer::STORE_RELATION => $storeRelationTransfer,
        ])->build();

        // Act
        $this->productRelationFacade->createProductRelation($productRelationTransfer);

        // Assert
        $productRelationExist = SpyProductRelationQuery::create()
            ->filterByProductRelationKey(static::SECOND_FIXTURE_VALUE)
            ->exists();
        $storeRelationExist = SpyProductRelationStoreQuery::create()
            ->useProductRelationQuery()
                ->filterByProductRelationKey(static::SECOND_FIXTURE_VALUE)
            ->endUse()
            ->exists();

        $this->assertTrue($productRelationExist, 'Product relation should exists');
        $this->assertTrue($storeRelationExist, 'Product relation store relation should exists');
    }
}
