<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group GetReturnReasonsTest
 * Add your own group annotations below this line
 */
class GetReturnReasonsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureReturnReasonTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsRetrievesReturnReasons(): void
    {
        // Arrange
        $returnReasonTransfers = $this->tester->haveReturnReasons([
            'return.return_reasons.fake_reason_1.name',
            'return.return_reasons.fake_reason_2.name',
        ]);

        $returnReasonFilterTransfer = new ReturnReasonFilterTransfer();

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertNotEmpty($returnReasonCollectionTransfer->getReturnReasons());
        $this->assertEquals(
            $returnReasonTransfers,
            $returnReasonCollectionTransfer->getReturnReasons()->getArrayCopy()
        );
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsRetrievesReturnReasonsWithFilter(): void
    {
        // Arrange
        $this->tester->haveReturnReasons([
            'return.return_reasons.fake_reason_1.name',
            'return.return_reasons.fake_reason_2.name',
        ]);

        $returnReasonFilterTransfer = (new ReturnReasonFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit(1));

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertCount(1, $returnReasonCollectionTransfer->getReturnReasons());
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsEnsureThatPaginationNbResultsExists(): void
    {
        // Arrange
        $this->tester->haveReturnReasons([
            'return.return_reasons.fake_reason_1.name',
            'return.return_reasons.fake_reason_2.name',
            'return.return_reasons.fake_reason_3.name',
            'return.return_reasons.fake_reason_4.name',
            'return.return_reasons.fake_reason_5.name',
        ]);

        $returnReasonFilterTransfer = (new ReturnReasonFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit(1));

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertCount(1, $returnReasonCollectionTransfer->getReturnReasons());
        $this->assertSame(5, $returnReasonCollectionTransfer->getPagination()->getNbResults());
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsInCaseEmptyTable(): void
    {
        // Arrange
        $returnReasonFilterTransfer = new ReturnReasonFilterTransfer();

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertEmpty($returnReasonCollectionTransfer->getReturnReasons());
    }
}
