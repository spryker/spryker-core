<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentQuoteRequest\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter;
use Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface;
use Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group AgentQuoteRequest
 * @group Business
 * @group AgentQuoteRequestWriterTest
 * Add your own group annotations below this line
 */
class AgentQuoteRequestWriterTest extends Unit
{
    protected const FAKE_ID_QUOTE_REQUEST_VERSION = 'FAKE_ID_QUOTE_REQUEST_VERSION';

    /**
     * @uses \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @uses \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $agentQuoteRequestWriter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->agentQuoteRequestWriter = $this->createAgentQuoteRequestWriterMock();
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertEquals(
            SharedAgentQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithoutReference(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->cancelByReference(new QuoteRequestFilterTransfer());
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithWrongReference(): void
    {
        // Arrange
        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn(null);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertCount(1, $quoteRequestResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getErrors()[0]
        );
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithAlreadyCanceledStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_CANCELED,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertCount(1, $quoteRequestResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getErrors()[0]
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter
     */
    protected function createAgentQuoteRequestWriterMock(): AgentQuoteRequestWriter
    {
        return $this->getMockBuilder(AgentQuoteRequestWriter::class)
            ->setMethods(['findQuoteRequest'])
            ->setConstructorArgs([
                $this->createAgentQuoteRequestToQuoteRequestInterfaceMock(),
                $this->createAgentQuoteRequestEntityManagerInterfaceMock(),
                $this->createQuoteRequestConfigMock(),
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface
     */
    protected function createAgentQuoteRequestToQuoteRequestInterfaceMock(): AgentQuoteRequestToQuoteRequestInterface
    {
        return $this->getMockBuilder(AgentQuoteRequestToQuoteRequestInterface::class)
            ->setMethods(['getQuoteRequestCollectionByFilter'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface
     */
    protected function createAgentQuoteRequestEntityManagerInterfaceMock(): AgentQuoteRequestEntityManagerInterface
    {
        $agentQuoteRequestEntityManagerInterface = $this->getMockBuilder(AgentQuoteRequestEntityManagerInterface::class)
            ->setMethods(['updateQuoteRequest'])
            ->disableOriginalConstructor()
            ->getMock();

        $agentQuoteRequestEntityManagerInterface->method('updateQuoteRequest')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer;
            });

        return $agentQuoteRequestEntityManagerInterface;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig
     */
    protected function createQuoteRequestConfigMock(): AgentQuoteRequestConfig
    {
        $quoteRequestConfigMock = $this->getMockBuilder(AgentQuoteRequestConfig::class)
            ->setMethods(['getCancelableStatuses'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteRequestConfigMock
            ->method('getCancelableStatuses')
            ->willReturn([
                SharedAgentQuoteRequestConfig::STATUS_DRAFT,
                SharedAgentQuoteRequestConfig::STATUS_WAITING,
                SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
                SharedAgentQuoteRequestConfig::STATUS_READY,
            ]);

        return $quoteRequestConfigMock;
    }
}
