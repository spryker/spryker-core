<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroupCollector\Persistence\Collector\Propel;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel\ProductGroupCollectorQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductGroupCollector
 * @group Persistence
 * @group Collector
 * @group Propel
 * @group ProductGroupCollectorQueryTest
 * Add your own group annotations below this line
 */
class ProductGroupCollectorQueryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductGroupCollector\ProductGroupCollectorPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPrepareQueryWillPrepareQueryThatReturnCorrectProductGroupData(): void
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

        $productGroupCollectorQuery = new ProductGroupCollectorQuery();
        $productGroupCollectorQuery->setTouchQuery(SpyTouchQuery::create());

        // Act
        $productGroupCollectorQuery->prepare();
        $result = $productGroupCollectorQuery->getTouchQuery()->find()->toArray();

        // Assert
        $this->assertNotEmpty($result);
        $resultProductGroupData1 = $this->findResultProductGroupData($result, $productGroupTransfer1->getIdProductGroup());
        $this->assertNotNull($resultProductGroupData1);
        $this->assertEquals(
            implode(',', $productGroupTransfer1->getIdProductAbstracts()),
            $resultProductGroupData1[ProductGroupCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]
        );

        $resultProductGroupData2 = $this->findResultProductGroupData($result, $productGroupTransfer2->getIdProductGroup());
        $this->assertNotNull($resultProductGroupData2);
        $this->assertEquals(
            implode(',', $productGroupTransfer2->getIdProductAbstracts()),
            $resultProductGroupData2[ProductGroupCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]
        );
    }

    /**
     * @param array $result
     * @param int $idProductGroup
     *
     * @return array|null
     */
    protected function findResultProductGroupData(array $result, int $idProductGroup): ?array
    {
        foreach ($result as $row) {
            if ($row['ItemType'] === 'product_group' && $row['ItemId'] === $idProductGroup) {
                return $row;
            }
        }

        return null;
    }
}
