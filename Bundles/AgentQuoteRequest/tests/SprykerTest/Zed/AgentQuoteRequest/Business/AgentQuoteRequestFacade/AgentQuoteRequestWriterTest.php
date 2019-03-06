<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentQuoteRequest\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig;
use Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter;
use Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface;

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
     * @return void
     */
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->setQuoteRequestEditable($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertEquals(
            SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgressWithoutReference(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->setQuoteRequestEditable(new QuoteRequestFilterTransfer());
    }

    /**
     * @return void
     */
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgressWithWrongReference(): void
    {
        // Arrange
        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn(null);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->setQuoteRequestEditable($quoteRequestFilterTransfer);

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
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgressWithAlreadyInProgressStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->setQuoteRequestEditable($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertCount(1, $quoteRequestResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getErrors()[0]
        );
    }

    /**
     * @return void
     */
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgressWithoutLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->setQuoteRequestEditable($quoteRequestFilterTransfer);
    }

    /**
     * @return void
     */
    public function testSetQuoteRequestEditableChangesQuoteRequestStatusToInProgressWithoutQuoteInLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion((new QuoteRequestVersionBuilder())->build());

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->setQuoteRequestEditable($quoteRequestFilterTransfer);
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
                $this->createQuoteRequestConfigMock(),
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface
     */
    protected function createAgentQuoteRequestToQuoteRequestInterfaceMock(): AgentQuoteRequestToQuoteRequestInterface
    {
        $agentQuoteRequestToQuoteRequestInterface = $this->getMockBuilder(AgentQuoteRequestToQuoteRequestInterface::class)
            ->setMethods(['getQuoteRequestCollectionByFilter', 'update'])
            ->disableOriginalConstructor()
            ->getMock();

        $agentQuoteRequestToQuoteRequestInterface
            ->method('update')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return (new QuoteRequestResponseTransfer())->setQuoteRequest($quoteRequestTransfer)->setIsSuccess(true);
            });

        return $agentQuoteRequestToQuoteRequestInterface;
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
