<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
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
 * @group UpdateProductRelationTest
 * Add your own group annotations below this line
 */
class UpdateProductRelationTest extends Unit
{
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
    public function testUpdateProductRelationShouldUpdateProductRelationWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensureProductRelationTableIsEmpty();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $relatedProductAbstractTransfer = $this->tester->haveProductAbstract();

        $productRelationTransfer = $this->tester->haveProductRelation(
            $productAbstractTransfer->getSku(),
            $relatedProductAbstractTransfer->getIdProductAbstract(),
            'test'
        );

        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_ENTITY => $productRelationTransfer->getIdProductRelation(),
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $productRelationTransfer->setStoreRelation($storeRelationTransfer);
        $productRelationTransfer->setProductRelationKey('test');

        // Act
        $this->productRelationFacade->updateProductRelation($productRelationTransfer);

        // Assert
        $resultProductRelationEntity = SpyProductRelationQuery::create()
            ->filterByIdProductRelation($productRelationTransfer->getIdProductRelation())
            ->findOne();

        $storeRelationExist = SpyProductRelationStoreQuery::create()
            ->filterByFkProductRelation($productRelationTransfer->getIdProductRelation())
            ->exists();

        $this->assertSame('test', $resultProductRelationEntity->getProductRelationKey(), 'Product relation key should match to the expected value');
        $this->assertTrue($storeRelationExist, 'Product relation store relation should exists');
    }
}
