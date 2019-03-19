<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestUserWriter;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group QuoteRequestUserWriterTest
 * Add your own group annotations below this line
 */
class QuoteRequestUserWriterTest extends Unit
{
    protected const FAKE_ID_QUOTE_REQUEST_VERSION = 'FAKE_ID_QUOTE_REQUEST_VERSION';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestUserWriter::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestUserWriter::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestUserWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRequestUserWriter;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRequestUserWriter = $this->createQuoteRequestUserWriterMock();
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->cancelQuoteRequestByUser($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CANCELED,
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
        $this->quoteRequestUserWriter->cancelQuoteRequestByUser(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceledWithWrongReference(): void
    {
        // Arrange
        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->cancelQuoteRequestByUser($quoteRequestCriteriaTransfer);

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
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_CANCELED,
        ]))->build();

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->cancelQuoteRequestByUser($quoteRequestCriteriaTransfer);

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
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
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
        $this->quoteRequestUserWriter->markQuoteRequestInProgress(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithWrongReference(): void
    {
        // Arrange
        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

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
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
        ]))->build();

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestUserWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

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
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->quoteRequestUserWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithoutQuoteInLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion((new QuoteRequestVersionBuilder())->build());

        $this->quoteRequestUserWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->quoteRequestUserWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestUserWriterMock(): MockObject
    {
        $quoteRequestUserWriterMock = $this->getMockBuilder(QuoteRequestUserWriter::class)
            ->setMethods(['findQuoteRequestTransfer', 'findCustomerReference'])
            ->setConstructorArgs([
                $this->createQuoteRequestConfigMock(),
                $this->createQuoteRequestEntityManagerInterfaceMock(),
                $this->createQuoteRequestRepositoryInterfaceMock(),
                $this->createQuoteRequestReferenceGeneratorInterfaceMock(),
                $this->createQuoteRequestToCompanyUserInterfaceMock(),
            ])
            ->getMock();

        $quoteRequestUserWriterMock->method('findCustomerReference')
            ->willReturn('');

        return $quoteRequestUserWriterMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestEntityManagerInterfaceMock(): MockObject
    {
        $quoteRequestEntityManagerInterface = $this->getMockBuilder(QuoteRequestEntityManagerInterface::class)
            ->setMethods([
                'createQuoteRequest',
                'updateQuoteRequest',
                'createQuoteRequestVersion',
                'updateQuoteRequestVersion',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteRequestEntityManagerInterface
            ->method('updateQuoteRequest')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer;
            });

        $quoteRequestEntityManagerInterface
            ->method('createQuoteRequest')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer;
            });

        return $quoteRequestEntityManagerInterface;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestConfigMock(): MockObject
    {
        $quoteRequestConfigMock = $this->getMockBuilder(QuoteRequestConfig::class)
            ->setMethods(['getUserCancelableStatuses'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteRequestConfigMock
            ->method('getUserCancelableStatuses')
            ->willReturn([
                SharedQuoteRequestConfig::STATUS_DRAFT,
                SharedQuoteRequestConfig::STATUS_WAITING,
                SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
                SharedQuoteRequestConfig::STATUS_READY,
            ]);

        return $quoteRequestConfigMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestRepositoryInterfaceMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestReferenceGeneratorInterfaceMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestReferenceGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestToCompanyUserInterfaceMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestToCompanyUserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
