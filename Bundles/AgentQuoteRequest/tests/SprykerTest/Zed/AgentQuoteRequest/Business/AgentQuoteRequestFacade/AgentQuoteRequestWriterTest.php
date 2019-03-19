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
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
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
     * @uses \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @uses \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest\AgentQuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

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
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedAgentQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceledWithoutReference(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->cancelQuoteRequest(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceledWithWrongReference(): void
    {
        // Arrange
        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceledWithAlreadyCanceledStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_CANCELED,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgress(): void
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
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithoutReference(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->markQuoteRequestInProgress(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithWrongReference(): void
    {
        // Arrange
        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithAlreadyInProgressStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->agentQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithoutLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithoutQuoteInLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedAgentQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion((new QuoteRequestVersionBuilder())->build());

        $this->agentQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestByReference')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->agentQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAgentQuoteRequestWriterMock(): MockObject
    {
        return $this->getMockBuilder(AgentQuoteRequestWriter::class)
            ->setMethods(['findQuoteRequestByReference'])
            ->setConstructorArgs([
                $this->createAgentQuoteRequestToQuoteRequestInterfaceMock(),
                $this->createQuoteRequestConfigMock(),
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAgentQuoteRequestToQuoteRequestInterfaceMock(): MockObject
    {
        $agentQuoteRequestToQuoteRequestInterface = $this->getMockBuilder(AgentQuoteRequestToQuoteRequestInterface::class)
            ->setMethods(['getQuoteRequestCollectionByFilter', 'updateQuoteRequest', 'sendQuoteRequestToCustomer'])
            ->disableOriginalConstructor()
            ->getMock();

        $agentQuoteRequestToQuoteRequestInterface
            ->method('updateQuoteRequest')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return (new QuoteRequestResponseTransfer())->setQuoteRequest($quoteRequestTransfer)->setIsSuccessful(true);
            });

        return $agentQuoteRequestToQuoteRequestInterface;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestConfigMock(): MockObject
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
