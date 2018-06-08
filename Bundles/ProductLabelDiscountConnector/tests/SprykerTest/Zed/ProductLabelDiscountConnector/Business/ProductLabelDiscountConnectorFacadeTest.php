<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelDiscountConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductLabelTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelDiscountConnector
 * @group Business
 * @group Facade
 * @group ProductLabelDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelDiscountConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabelDiscountConnector\ProductLabelDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindAllLabelsShoulsReturnListOfExistingLabels()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'label 1',
        ]);
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'label 2',
        ]);

        // Act
        $actualLabels = $this->tester->getFacade()->findAllLabels();

        // Assert
        $expectedLabels = [
            'label 1' => 'label 1',
            'label 2' => 'label 2',
        ];
        $this->assertArraySubset($expectedLabels, $actualLabels, 'Missing expected list of labels.');
    }

    /**
     * @return void
     */
    public function testIsProductLabelSatisfiedByShouldReturnTrueWhenLabelIsPresent()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer($productLabelTransfer->getName());

        // Act
        $isSatisfied = $this->tester->getFacade()->isProductLabelSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer
        );

        // Assert
        $this->assertTrue($isSatisfied, 'Quote should have been satisfied by product label.');
    }

    /**
     * @return void
     */
    public function testIsProductLabelSatisfiedByShouldReturnFalseWhenLabelIsNotPresent()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer($productLabelTransfer->getName());

        // Act
        $isSatisfied = $this->tester->getFacade()->isProductLabelSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()[0],
            $clauseTransfer
        );

        // Assert
        $this->assertFalse($isSatisfied, 'Quote should not have been satisfied by product label.');
    }

    /**
     * @return void
     */
    public function testCollectByProductLabelShouldCollectAllItemsMatchingLabel()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
        ]);

        $productConcreteTransfer1 = $this->tester->haveProduct();
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $productConcreteTransfer3 = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer1->getFkProductAbstract()
        );

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer2->getFkProductAbstract()
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([
            $productConcreteTransfer1,
            $productConcreteTransfer2,
            $productConcreteTransfer3,

        ]);
        $clauseTransfer = $this->tester->createClauseTransfer($productLabelTransfer->getName());

        // Act
        $collected = $this->tester->getFacade()->collectByProductLabel($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(2, $collected, 'Number of collected items should match expected number.');
    }

    /**
     * @return void
     */
    public function testCollectByExclusiveProductLabelShouldCollectItemsMatchingOnlyExclusiveLabel()
    {
        // Arrange
        $productLabelTransfer1 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::POSITION => 1,
        ]);
        $productLabelTransfer2 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::IS_EXCLUSIVE,
            ProductLabelTransfer::POSITION => 2,
        ]);
        $productLabelTransfer3 = $this->tester->haveProductLabel([
            ProductLabelTransfer::VALID_FROM => null,
            ProductLabelTransfer::VALID_TO => null,
            ProductLabelTransfer::POSITION => 3,
        ]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer3->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract()
        );

        $quoteTransfer = $this->tester->createQuoteTransfer([$productConcreteTransfer]);
        $clauseTransfer = $this->tester->createClauseTransfer($productLabelTransfer1->getName());

        // Act
        $collected = $this->tester->getFacade()->collectByProductLabel($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(1, $collected, 'Number of collected items should match expected number.');
    }
}
