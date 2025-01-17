<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderAmendmentQuoteCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderAmendmentQuoteCollectionTest extends Unit
{
 /**
  * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
  */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldDeleteSalesOrderAmendmentQuotesByIds(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer1 = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer2 = $this->tester->haveSalesOrderAmendmentQuote();
        $deleteCriteriaTransfer = (new SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer1->getIdSalesOrderAmendmentQuote())
            ->addIdSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer2->getIdSalesOrderAmendmentQuote());

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()->deleteSalesOrderAmendmentQuoteCollection($deleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertNull($this->tester->findSalesOrderAmendmentQuoteByUuid($salesOrderAmendmentQuoteTransfer1->getUuid()));
        $this->assertNull($this->tester->findSalesOrderAmendmentQuoteByUuid($salesOrderAmendmentQuoteTransfer2->getUuid()));
    }

    /**
     * @return void
     */
    public function testShouldDeleteSalesOrderAmendmentQuotesByUuids(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer1 = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer2 = $this->tester->haveSalesOrderAmendmentQuote();
        $deleteCriteriaTransfer = (new SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer())
            ->addUuid($salesOrderAmendmentQuoteTransfer1->getUuid())
            ->addUuid($salesOrderAmendmentQuoteTransfer2->getUuid());

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()->deleteSalesOrderAmendmentQuoteCollection($deleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertNull($this->tester->findSalesOrderAmendmentQuoteByUuid($salesOrderAmendmentQuoteTransfer1->getUuid()));
        $this->assertNull($this->tester->findSalesOrderAmendmentQuoteByUuid($salesOrderAmendmentQuoteTransfer2->getUuid()));
    }
}
