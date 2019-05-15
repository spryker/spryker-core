<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteRequestAgent;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverter;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToQuoteClientBridge;
use Spryker\Client\QuoteRequestAgent\Status\QuoteRequestAgentStatus;
use Spryker\Shared\QuoteRequestAgent\QuoteRequestAgentConfig as SharedQuoteRequestAgentConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group QuoteRequestAgent
 * @group QuoteRequestAgentConverterTest
 * Add your own group annotations below this line
 */
class QuoteRequestAgentConverterTest extends Unit
{
    /**
     * @uses \Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverter::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\QuoteRequestAgent\Converter\QuoteRequestAgentConverter
     */
    protected $quoteRequestAgentConverterMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRequestAgentConverterMock = $this->createQuoteRequestAgentConverterMock();
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithInProgressStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedQuoteRequestAgentConfig::STATUS_IN_PROGRESS);

        // Act
        $quoteResponseTransfer = $this->quoteRequestAgentConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedQuoteRequestAgentConfig::STATUS_WAITING);

        // Act
        $quoteResponseTransfer = $this->quoteRequestAgentConverterMock->convertQuoteRequestToQuote($quoteRequestTransfer);

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
    protected function createQuoteRequestAgentConverterMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestAgentConverter::class)
            ->setConstructorArgs([
                $this->createQuoteRequestAgentToQuoteClientBridgeMock(),
                $this->createQuoteRequestAgentStatusMock(),
            ])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestAgentToQuoteClientBridgeMock(): MockObject
    {
        return $this->createPartialMock(QuoteRequestAgentToQuoteClientBridge::class, ['setQuote']);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestAgentStatusMock(): MockObject
    {
        $quoteRequestAgentStatusMock = $this->getMockBuilder(QuoteRequestAgentStatus::class)
            ->disableOriginalConstructor()
            ->setMethods(['isQuoteRequestEditable'])
            ->getMock();

        $quoteRequestAgentStatusMock
            ->method('isQuoteRequestEditable')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer->getStatus() === SharedQuoteRequestAgentConfig::STATUS_IN_PROGRESS;
            });

        return $quoteRequestAgentStatusMock;
    }

    /**
     * @param string $status
     * @param string|null $validUntil
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(
        string $status = SharedQuoteRequestAgentConfig::STATUS_READY,
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
