<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductNew\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductNew
 * @group Business
 * @group Facade
 * @group ProductNewFacadeTest
 * Add your own group annotations below this line
 */
class ProductNewFacadeTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductNew\ProductNewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidTimeRangeResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-1 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('+1 minute')),
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign(), 'Number of products to be assigned should have matched the expected amount.');
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign(), 'Number of products to be deassigned should have matched the expected amount.');
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign()[0],
            'Product abstract to be assigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldNotReturnInvalidTimeRangeResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-2 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('-1 minute')),
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('+1 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('+2 minute')),
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldNotReturnAlreadyAssignedResults()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-1 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('+1 minute')),
        ]);

        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabel(), $productAbstractTransfer->getIdProductAbstract());

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @return void
     */
    public function testFindProductsToDeassignShouldReturnValidTimeRangeResults()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-2 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('-1 minute')),
        ]);

        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabel(), $productAbstractTransfer->getIdProductAbstract());

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign(), 'Number of products to be assigned should have matched the expected amount.');
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign(), 'Number of products to be deassigned should have matched the expected amount.');
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeassign()[0],
            'Product abstract to be deassigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testFindProductsToDeassignShouldNotReturnInvalidTimeRangeResults()
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-1 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('+1 minute')),
        ]);

        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabel(), $productAbstractTransfer->getIdProductAbstract());

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @return void
     */
    public function testFindProductsToDeassignShouldNotReturnNotAssignedResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->tester->getFacade()->getLabelNewName(),
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => date('Y-m-d H:i:s', strtotime('-2 minute')),
            ProductAbstractTransfer::NEW_TO => date('Y-m-d H:i:s', strtotime('-1 minute')),
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

}
