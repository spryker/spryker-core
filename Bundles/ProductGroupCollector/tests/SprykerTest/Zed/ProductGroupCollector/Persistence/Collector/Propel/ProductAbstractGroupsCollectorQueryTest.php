<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroupCollector\Persistence\Collector\Propel;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel\ProductAbstractGroupsCollectorQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductGroupCollector
 * @group Persistence
 * @group Collector
 * @group Propel
 * @group ProductAbstractGroupsCollectorQueryTest
 * Add your own group annotations below this line
 */
class ProductAbstractGroupsCollectorQueryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductGroupCollector\ProductGroupCollectorPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPrepareShouldReturnAbstractGroupsTouchQueryGroupedByAbstractProductId(): void
    {
        // Arrange
        $expectedCount = 4;

        $productAbstract1Id = $this->tester->haveProductAbstract()
            ->getIdProductAbstract();
        $productAbstract2Id = $this->tester->haveProductAbstract()
            ->getIdProductAbstract();

        $groupsOverrideData = [
            [
                ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                    $productAbstract1Id,
                    $productAbstract2Id,
                ],
            ],
            [
                ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                    $productAbstract1Id,
                ],
            ],
        ];

        foreach ($groupsOverrideData as $groupOverrideData) {
            $this->tester->haveProductGroup($groupOverrideData);
        }

        $touchQuery = new SpyTouchQuery();
        $productAbstractGroupsCollectorQuery = new ProductAbstractGroupsCollectorQuery();
        $productAbstractGroupsCollectorQuery->setTouchQuery($touchQuery);

        // Act
        $preparedQuery = $productAbstractGroupsCollectorQuery->prepare()->getTouchQuery();

        // Assert
        $this->assertSame($expectedCount, $preparedQuery->count());
    }
}
