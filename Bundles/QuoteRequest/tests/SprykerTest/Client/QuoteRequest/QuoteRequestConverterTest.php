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
use Spryker\Client\QuoteRequest\Converter\QuoteRequestConverter;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;
use Spryker\Client\QuoteRequest\QuoteRequestConfig;
use Spryker\Client\QuoteRequest\Status\QuoteRequestStatus;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group QuoteRequest
 * @group QuoteRequestConverterTest
 * Add your own group annotations below this line
 */
class QuoteRequestConverterTest extends Unit
{
    /**
     * @uses \Spryker\Client\QuoteRequest\Converter\QuoteRequestConverter::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @uses \Spryker\Client\QuoteRequest\Converter\QuoteRequestConverter::GLOSSARY_KEY_WRONG_CONVERT_QUOTE_REQUEST_VALID_UNTIL
     */
    protected const GLOSSARY_KEY_WRONG_CONVERT_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.convert.error.wrong_valid_until';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\QuoteRequest\Converter\QuoteRequestConverter
     */
    protected $quoteRequestConverterMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRequestConverterMock = $this->createQuoteRequestConverterMock();
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithoutValidUntilDate(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer();

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithValidUntilDate(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(
            SharedQuoteRequestConfig::STATUS_READY,
            (new DateTime("+1 hour"))->format('Y-m-d H:i:s')
        );

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedQuoteRequestConfig::STATUS_WAITING);

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToLockedQuoteWithWrongValidUntilDate(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(
            SharedQuoteRequestConfig::STATUS_READY,
            (new DateTime("-1 hour"))->format('Y-m-d H:i:s')
        );

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_CONVERT_QUOTE_REQUEST_VALID_UNTIL,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithDraftStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedQuoteRequestConfig::STATUS_DRAFT);

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedQuoteRequestConfig::STATUS_WAITING);

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestConverterMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestConverter::class)
            ->setConstructorArgs([
                $this->createQuoteRequestToPersistentCartClientInterfaceMock(),
                $this->createQuoteRequestToQuoteClientInterfaceMock(),
                $this->createQuoteRequestToCartClientInterfaceMock(),
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
            ->setMethods(['persistQuote'])
            ->getMock();

        $quoteRequestToPersistentCartClientInterfaceMock->expects($this->any())
            ->method('persistQuote')
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
        $quoteRequestToQuoteClientInterfaceMock = $this->createPartialMock(QuoteRequestToQuoteClientInterface::class, ['setQuote']);

        return $quoteRequestToQuoteClientInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestToCartClientInterfaceMock(): MockObject
    {
        $quoteRequestToCartClientInterfaceMock = $this->createPartialMock(QuoteRequestToCartClientInterface::class, ['lockQuote']);

        $quoteRequestToCartClientInterfaceMock->expects($this->any())
            ->method('lockQuote')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $quoteTransfer->setIsLocked(true);
            });

        return $quoteRequestToCartClientInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestCheckerMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestStatus::class)
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
     * @param string|null $validUntil
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(
        string $status = SharedQuoteRequestConfig::STATUS_READY,
        ?string $validUntil = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => $status,
            QuoteRequestTransfer::VALID_UNTIL => $validUntil,
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
