<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SalesOrderAmendmentQuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
 * @group UpdateSalesOrderAmendmentQuoteCollectionTest
 * Add your own group annotations below this line
 */
class UpdateSalesOrderAmendmentQuoteCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\SalesOrderAmendmentQuoteValidator::ERROR_NOT_FOUND_ENTITY
     *
     * @var string
     */
    protected const ERROR_NOT_FOUND_ENTITY = 'Entity with ID `%d` was not found in the database.';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldUpdateQuotes(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->setAmendmentOrderReference('new-reference');

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionResponseTransfer->getSalesOrderAmendmentQuotes());

        $persistedSalesOrderAmendmentQuote = $this->tester->findSalesOrderAmendmentQuoteByUuid(
            $salesOrderAmendmentQuoteTransfer->getUuidOrFail(),
        );
        $this->assertNotNull($persistedSalesOrderAmendmentQuote);
        $this->assertSame('new-reference', $persistedSalesOrderAmendmentQuote->getAmendmentOrderReference());
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldReturnErrorWhenQuoteNotFound(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
        $salesOrderAmendmentQuoteTransfer = (new SalesOrderAmendmentQuoteBuilder())->build();
        $salesOrderAmendmentQuoteTransfer->setIdSalesOrderAmendmentQuote(123)
            ->setQuote(new QuoteTransfer());

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionResponseTransfer = $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors());
        $this->assertSame(sprintf(static::ERROR_NOT_FOUND_ENTITY, 123), $salesOrderAmendmentQuoteCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage());
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenIdSalesOrderAmendmentQuoteIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->setIdSalesOrderAmendmentQuote(null);

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "idSalesOrderAmendmentQuote" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->setQuote(null);

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "quote" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenCustomerReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->setCustomerReference(null);

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "customerReference" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderAmendmentQuoteCollectionShouldThrowExceptionWhenAmendmentOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->setAmendmentOrderReference(null);

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "amendmentOrderReference" for transfer %s.', SalesOrderAmendmentQuoteTransfer::class));

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldFilterQuoteFieldsDuringUpdate(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
        $this->tester->mockConfigMethod('getQuoteFieldsAllowedForSaving', [QuoteTransfer::ITEMS]);
        $salesOrderAmendmentQuoteTransfer = $this->tester->haveSalesOrderAmendmentQuote();
        $salesOrderAmendmentQuoteTransfer->getQuoteOrFail()
            ->setName('quote-name')
            ->addItem((new ItemTransfer())->setSku('sku-1'));

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Act
        $this->tester->getFacade()
            ->updateSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);

        // Assert
        $persistedQuoteData = $this->tester->findSalesOrderAmendmentQuoteByUuid(
            $salesOrderAmendmentQuoteTransfer->getUuidOrFail(),
        )->getQuoteData();

        $this->assertJsonStringEqualsJsonString('{"items":[{"sku":"sku-1"}]}', $persistedQuoteData);
    }
}
