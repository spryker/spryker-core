<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group CreateSalesOrderAmendmentQuoteCollectionTest
 * Add your own group annotations below this line
 */
class CreateSalesOrderAmendmentQuoteCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreateSalesOrderAmendmentQuoteCollectionShouldPersistQuotes(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionRequestTransfer = $this->tester->createSalesOrderAmendmentQuoteCollectionRequestTransfer();

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertCount(2, $salesOrderAmendmentQuoteCollectionResponseTransfer->getSalesOrderAmendmentQuotes());

        $this->assertNotNull($this->tester->findSalesOrderAmendmentQuoteByUuid(
            $salesOrderAmendmentQuoteCollectionResponseTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->getUuid(),
        ));
        $this->assertNotNull($this->tester->findSalesOrderAmendmentQuoteByUuid(
            $salesOrderAmendmentQuoteCollectionResponseTransfer->getSalesOrderAmendmentQuotes()->offsetGet(1)->getUuid(),
        ));
    }

    /**
     * @return void
     */
    public function testCreateSalesOrderAmendmentQuoteCollectionShouldPersistQuotesWhenStoreNameIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionRequestTransfer = $this->tester->createSalesOrderAmendmentQuoteCollectionRequestTransfer();
        $salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->setStore(null);

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertCount(2, $salesOrderAmendmentQuoteCollectionResponseTransfer->getSalesOrderAmendmentQuotes());
    }

    /**
     * @return void
     */
    public function testCreateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenCustomerReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionRequestTransfer = $this->tester->createSalesOrderAmendmentQuoteCollectionRequestTransfer();
        $salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->setCustomerReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "customerReference" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenAmendmentOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionRequestTransfer = $this->tester->createSalesOrderAmendmentQuoteCollectionRequestTransfer();
        $salesOrderAmendmentQuoteCollectionRequestTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0)->setAmendmentOrderReference(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "amendmentOrderReference" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenSalesOrderAmendmentQuotesIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionRequestTransfer = new SalesOrderAmendmentQuoteCollectionRequestTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Empty required collection property "salesOrderAmendmentQuotes" for transfer ' . SalesOrderAmendmentQuoteCollectionRequestTransfer::class);

        // Act
        $this->tester->getFacade()
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }
}
