<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\AgentQuoteRequest;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientBridge;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestChecker;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group AgentQuoteRequest
 * @group QuoteRequestConverterTest
 * Add your own group annotations below this line
 */
class QuoteRequestConverterTest extends Unit
{
    /**
     * @uses \Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter
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
    public function testConvertQuoteRequestToQuoteWithInProgressStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS);

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
        $quoteRequestTransfer = $this->createQuoteRequestTransfer(SharedAgentQuoteRequestConfig::STATUS_WAITING);

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
                $this->createAgentQuoteRequestToQuoteClientBridgeMock(),
                $this->createQuoteRequestCheckerMock(),
            ])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAgentQuoteRequestToQuoteClientBridgeMock(): MockObject
    {
        return $this->createPartialMock(AgentQuoteRequestToQuoteClientBridge::class, ['setQuote']);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestCheckerMock(): MockObject
    {
        $quoteRequestCheckerMock = $this->getMockBuilder(QuoteRequestChecker::class)
            ->disableOriginalConstructor()
            ->setMethods(['isQuoteRequestEditable'])
            ->getMock();

        $quoteRequestCheckerMock
            ->method('isQuoteRequestEditable')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer->getStatus() === SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS;
            });

        return $quoteRequestCheckerMock;
    }

    /**
     * @param string $status
     * @param string|null $validUntil
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(
        string $status = SharedAgentQuoteRequestConfig::STATUS_READY,
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
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
