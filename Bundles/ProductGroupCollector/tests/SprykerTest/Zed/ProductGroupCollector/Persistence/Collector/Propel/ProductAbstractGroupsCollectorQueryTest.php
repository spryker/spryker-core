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
    protected const ITEM_TYPE_PRODUCT_GROUP = 'product_abstract_groups';
    protected const KEY_ITEM_TYPE = 'ItemType';
    protected const KEY_ITEM_ID = 'ItemId';

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
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productGroupTransfer1 = $this->tester->haveProductGroup([
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);
        $productGroupTransfer2 = $this->tester->haveProductGroup([
            ProductGroupTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
            ],
        ]);

        $touchQuery = new SpyTouchQuery();
        $productAbstractGroupsCollectorQuery = new ProductAbstractGroupsCollectorQuery();
        $productAbstractGroupsCollectorQuery->setTouchQuery($touchQuery);

        // Act
        $result = $productAbstractGroupsCollectorQuery->prepare()
            ->getTouchQuery()
            ->filterByItemType(static::ITEM_TYPE_PRODUCT_GROUP)
            ->find()
            ->toArray();

        // Assert
        $this->assertNotEmpty($result);
        $resultProductAbstractGroupData1 = $this->findResultProductAbstractGroupData($result, $productAbstractTransfer1->getIdProductAbstract());
        $this->assertNotNull($resultProductAbstractGroupData1);
        $this->assertEquals(
            implode(',', [$productGroupTransfer1->getIdProductGroup(), $productGroupTransfer2->getIdProductGroup()]),
            $resultProductAbstractGroupData1[ProductAbstractGroupsCollectorQuery::FIELD_ID_PRODUCT_GROUPS]
        );

        $resultProductAbstractGroupData2 = $this->findResultProductAbstractGroupData($result, $productAbstractTransfer2->getIdProductAbstract());
        $this->assertNotNull($resultProductAbstractGroupData2);
        $this->assertEquals(
            $productGroupTransfer1->getIdProductGroup(),
            $resultProductAbstractGroupData2[ProductAbstractGroupsCollectorQuery::FIELD_ID_PRODUCT_GROUPS]
        );
    }

    /**
     * @param array $result
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function findResultProductAbstractGroupData(array $result, int $idProductAbstract): ?array
    {
        foreach ($result as $row) {
            if ($row[static::KEY_ITEM_TYPE] === static::ITEM_TYPE_PRODUCT_GROUP && $row[static::KEY_ITEM_ID] === $idProductAbstract) {
                return $row;
            }
        }

        return null;
    }
}
