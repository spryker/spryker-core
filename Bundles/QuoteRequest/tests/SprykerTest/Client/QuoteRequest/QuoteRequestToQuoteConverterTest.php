<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteRequest;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestChecker;
use Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverter;
use Spryker\Client\QuoteRequest\QuoteRequestConfig;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group QuoteRequest
 * @group QuoteRequestToQuoteConverterTest
 * Add your own group annotations below this line
 */
class QuoteRequestToQuoteConverterTest extends Unit
{
    /**
     * @uses \Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverter::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @uses \Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverter::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\QuoteRequest\QuoteRequest\QuoteRequestToQuoteConverter
     */
    protected $quoteRequestToQuoteConverterMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRequestToQuoteConverterMock = $this->createQuoteRequestToQuoteConverterMock();
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithValidQuoteRequestSuccessful(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createValidQuoteRequestTransferInStatus(SharedQuoteRequestConfig::STATUS_READY);

        // Act
        $quoteResponseTransfer = $this->quoteRequestToQuoteConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithValidQuoteRequestSuccessful(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createValidQuoteRequestTransferInStatus(SharedQuoteRequestConfig::STATUS_DRAFT);

        // Act
        $quoteResponseTransfer = $this->quoteRequestToQuoteConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteLocksQuote(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createValidQuoteRequestTransferInStatus(SharedQuoteRequestConfig::STATUS_READY);

        $quoteClientMock = $this->createQuoteRequestToQuoteClientInterfaceMock();

        $quoteRequestToQuoteConverterMock = $this->getMockBuilder(QuoteRequestToQuoteConverter::class)
            ->setConstructorArgs([
                $this->createQuoteRequestToPersistentCartClientInterfaceMock(),
                $quoteClientMock,
                $this->createQuoteRequestCheckerMock(),
            ])
            ->setMethods(null)
            ->getMock();

        //Assert
        $quoteClientMock->method('setQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                $this->assertTrue($quoteTransfer->getIsLocked());
            });

        // Act
        $quoteRequestToQuoteConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithWrongStatusFails(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        // Act
        $quoteResponseTransfer = $this->quoteRequestToQuoteConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithWrongStatusFails(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_READY,
        ]))->build();

        // Act
        $quoteResponseTransfer = $this->quoteRequestToQuoteConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestToQuoteConverterMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestToQuoteConverter::class)
            ->setConstructorArgs([
                $this->createQuoteRequestToPersistentCartClientInterfaceMock(),
                $this->createQuoteRequestToQuoteClientInterfaceMock(),
                $this->createQuoteRequestCheckerMock(),
            ])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestToPersistentCartClientInterfaceMock(): MockObject
    {
        $quoteRequestToPersistentCartClientInterfaceMock = $this->getMockBuilder(QuoteRequestToPersistentCartClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['persistCustomerQuote'])
            ->getMock();

        $quoteRequestToPersistentCartClientInterfaceMock->expects($this->any())
            ->method('persistCustomerQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return (new QuoteResponseTransfer())->setQuoteTransfer($quoteTransfer)->setIsSuccessful(true);
            });

        return $quoteRequestToPersistentCartClientInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestToQuoteClientInterfaceMock(): MockObject
    {
        $quoteRequestToQuoteClientInterfaceMock = $this->createPartialMock(QuoteRequestToQuoteClientInterface::class, ['lockQuote', 'setQuote']);

        $quoteRequestToQuoteClientInterfaceMock->expects($this->any())
            ->method('lockQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer->setIsLocked(true);
            });

        return $quoteRequestToQuoteClientInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestCheckerMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestChecker::class)
            ->setConstructorArgs([$this->createQuoteRequestConfigMock()])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestConfigMock(): MockObject
    {
        $quoteRequestConfigMock = $this->getMockBuilder(QuoteRequestConfig::class)
            ->setMethods(['getCancelableStatuses'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteRequestConfigMock
            ->method('getCancelableStatuses')
            ->willReturn([
                SharedQuoteRequestConfig::STATUS_DRAFT,
                SharedQuoteRequestConfig::STATUS_WAITING,
                SharedQuoteRequestConfig::STATUS_READY,
            ]);

        return $quoteRequestConfigMock;
    }

    /**
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createValidQuoteRequestTransferInStatus(string $status): QuoteRequestTransfer
    {
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => $status,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime("+1 hour"))->format('Y-m-d H:i:s'),
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
