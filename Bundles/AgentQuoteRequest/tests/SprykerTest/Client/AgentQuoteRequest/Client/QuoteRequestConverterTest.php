<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteRequest\Client;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientInterface;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestChecker;
use Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group QuoteRequest
 * @group Client
 * @group QuoteRequestConverterTest
 * Add your own group annotations below this line
 */
class QuoteRequestConverterTest extends Unit
{
    /**
     * @uses \Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

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
    public function testConvertQuoteRequestToEditableQuoteWithConvertibleQuoteRequest(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
            QuoteRequestTransfer::QUOTE_IN_PROGRESS => $quoteTransfer,
        ]))->build();

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToQuoteInProgress($quoteRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testConvertQuoteRequestToQuoteWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        // Act
        $quoteResponseTransfer = $this->quoteRequestConverterMock->convertQuoteRequestToQuoteInProgress($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS,
            $quoteResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestConverter
     */
    protected function createQuoteRequestConverterMock(): QuoteRequestConverter
    {
        return $this->getMockBuilder(QuoteRequestConverter::class)
            ->setConstructorArgs([
                $this->createAgentQuoteRequestToQuoteClientInterfaceMock(),
                $this->createQuoteRequestCheckerMock(),
            ])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientInterface
     */
    protected function createAgentQuoteRequestToQuoteClientInterfaceMock(): AgentQuoteRequestToQuoteClientInterface
    {
        $agentQuoteRequestToQuoteClientInterfaceMock = $this->getMockBuilder(AgentQuoteRequestToQuoteClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote', 'setQuote'])
            ->getMock();

        $agentQuoteRequestToQuoteClientInterfaceMock->expects($this->any())
            ->method('getQuote')
            ->willReturn(new QuoteTransfer());

        return $agentQuoteRequestToQuoteClientInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestChecker
     */
    protected function createQuoteRequestCheckerMock(): QuoteRequestChecker
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
}
