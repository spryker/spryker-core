<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface;
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
 * @group QuoteRequestWriterTest
 * Add your own group annotations below this line
 */
class QuoteRequestWriterTest extends Unit
{
    protected const FAKE_ID_QUOTE_REQUEST_VERSION = 'FAKE_ID_QUOTE_REQUEST_VERSION';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRequestWriter;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->companyUserTransfer = (new CompanyUserBuilder())
            ->withCustomer()
            ->build()
            ->setIdCompanyUser('');

        $this->quoteRequestWriter = $this->createQuoteRequestWriterMock();
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
            QuoteRequestTransfer::COMPANY_USER => $this->companyUserTransfer,
        ]))->build();

        $this->quoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CANCELED,
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
        $this->quoteRequestWriter->cancelByReference(new QuoteRequestFilterTransfer());
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithoutCompanyUser(): void
    {
        // Arrange
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->quoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithoutIdCompanyUser(): void
    {
        // Arrange
        $this->companyUserTransfer->setIdCompanyUser(null);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->quoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithWrongReference(): void
    {
        // Arrange
        $this->quoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn(null);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_ID_QUOTE_REQUEST_VERSION);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

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
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceledWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            QuoteRequestTransfer::COMPANY_USER => $this->companyUserTransfer,
        ]))->build();

        $this->quoteRequestWriter->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestWriter->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertCount(1, $quoteRequestResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getErrors()[0]
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter
     */
    protected function createQuoteRequestWriterMock(): QuoteRequestWriter
    {
        return $this->getMockBuilder(QuoteRequestWriter::class)
            ->setMethods(['findQuoteRequest'])
            ->setConstructorArgs([
                $this->createQuoteRequestConfigMock(),
                $this->createQuoteRequestEntityManagerInterfaceMock(),
                $this->createQuoteRequestRepositoryInterfaceMock(),
                $this->createQuoteRequestReferenceGeneratorInterfaceMock(),
                $this->createQuoteRequestToCompanyUserInterfaceMock(),
                $this->createQuoteRequestToCalculationInterfaceMock(),
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected function createQuoteRequestEntityManagerInterfaceMock(): QuoteRequestEntityManagerInterface
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

        $quoteRequestEntityManagerInterface->method('updateQuoteRequest')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return $quoteRequestTransfer;
            });

        return $quoteRequestEntityManagerInterface;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected function createQuoteRequestConfigMock(): QuoteRequestConfig
    {
        return $this->getMockBuilder(QuoteRequestConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected function createQuoteRequestRepositoryInterfaceMock(): QuoteRequestRepositoryInterface
    {
        return $this->getMockBuilder(QuoteRequestRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    protected function createQuoteRequestReferenceGeneratorInterfaceMock(): QuoteRequestReferenceGeneratorInterface
    {
        return $this->getMockBuilder(QuoteRequestReferenceGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface
     */
    protected function createQuoteRequestToCompanyUserInterfaceMock(): QuoteRequestToCompanyUserInterface
    {
        return $this->getMockBuilder(QuoteRequestToCompanyUserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface
     */
    protected function createQuoteRequestToCalculationInterfaceMock(): QuoteRequestToCalculationInterface
    {
        return $this->getMockBuilder(QuoteRequestToCalculationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
