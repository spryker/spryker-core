<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelCollector\Persistence\Collector\Propel;

use Codeception\Test\Unit;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel\ProductAbstractRelationCollectorQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelCollector
 * @group Persistence
 * @group Collector
 * @group Propel
 * @group ProductAbstractRelationCollectorQueryTest
 * Add your own group annotations below this line
 */
class ProductAbstractRelationCollectorQueryTest extends Unit
{
    protected const COL_ITEM_ID = 'ItemId';

    /**
     * @var \SprykerTest\Zed\ProductLabelCollector\ProductLabelCollectorPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPrepareQueryWillPrepareQueryThatReturnCorrectProductAbstractRelationData(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $productLabelTransfer2 = $this->tester->haveProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract()
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract()
        );

        $productAbstractRelationCollectorQuery = new ProductAbstractRelationCollectorQuery();
        $productAbstractRelationCollectorQuery->setTouchQuery(SpyTouchQuery::create());

        // Act
        $result = $productAbstractRelationCollectorQuery->prepare()
            ->getTouchQuery()
            ->find()
            ->toArray();

        // Assert
        $this->assertNotEmpty($result);
        $resultProductAbstractRelationData = $this->findResultProductAbstractRelationData($result, $productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($resultProductAbstractRelationData);
        $expectedIdProductLabelsCsv = $productLabelTransfer1->getIdProductLabel()
            . ProductAbstractRelationCollectorQuery::LABEL_DELIMITER
            . 't,'
            . $productLabelTransfer2->getIdProductLabel()
            . ProductAbstractRelationCollectorQuery::LABEL_DELIMITER
            . 't';
        $this->assertEquals($expectedIdProductLabelsCsv, $resultProductAbstractRelationData[ProductAbstractRelationCollectorQuery::RESULT_FIELD_ID_PRODUCT_LABELS_CSV]);
    }

    /**
     * @param array $result
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function findResultProductAbstractRelationData(array $result, int $idProductAbstract): ?array
    {
        foreach ($result as $row) {
            if ($row['ItemType'] === 'product_abstract' && $row['ItemId'] === $idProductAbstract) {
                return $row;
            }
        }

        return null;
    }
}
