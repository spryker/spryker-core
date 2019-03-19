<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter;
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
 * @group UserQuoteRequestWriterTest
 * Add your own group annotations below this line
 */
class UserQuoteRequestWriterTest extends Unit
{
    protected const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';
    protected const FAKE_QUOTE_REQUEST_REFERENCE = 'FAKE_QUOTE_REQUEST_REFERENCE';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $userQuoteRequestWriter;

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
            ->withCustomer([
                CustomerTransfer::CUSTOMER_REFERENCE => static::FAKE_CUSTOMER_REFERENCE,
            ])
            ->build()
            ->setIdCompanyUser('');

        $this->userQuoteRequestWriter = $this->createUserQuoteRequestWriterMock();
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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

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
        $this->userQuoteRequestWriter->cancelQuoteRequest(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceledWithWrongReference(): void
    {
        // Arrange
        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->cancelQuoteRequest($quoteRequestCriteriaTransfer);

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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

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
        $this->userQuoteRequestWriter->markQuoteRequestInProgress(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testMarkQuoteRequestInProgressChangesQuoteRequestStatusToInProgressWithWrongReference(): void
    {
        // Arrange
        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);

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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->userQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
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

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->userQuoteRequestWriter->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerCreatesLatestVersionWithReadyStatus(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            QuoteRequestTransfer::QUOTE_IN_PROGRESS => $quoteTransfer,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime())->add(new DateInterval("PT1H"))->format('Y-m-d H:i:s'),
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertFalse($storedQuoteRequestTransfer->getIsHidden());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals($quoteTransfer, $storedQuoteRequestTransfer->getLatestVersion()->getQuote());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerWithoutQuoteRequestReference(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->userQuoteRequestWriter->sendQuoteRequestToCustomer(new QuoteRequestCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerWithoutQuoteRequest(): void
    {
        // Arrange
        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn(null);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

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
    public function testSendQuoteRequestToCustomerWithWrongQuoteRequestStatus(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_WAITING,
            QuoteRequestTransfer::QUOTE_IN_PROGRESS => $quoteTransfer,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime())->add(new DateInterval("PT1H"))->format('Y-m-d H:i:s'),
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

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
    public function testSendQuoteRequestToCustomerWithoutQuoteRequestValidUntil(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            QuoteRequestTransfer::QUOTE_IN_PROGRESS => $quoteTransfer,
            QuoteRequestTransfer::VALID_UNTIL => null,
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerWithWrongQuoteRequestValidUntil(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->build();

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            QuoteRequestTransfer::QUOTE_IN_PROGRESS => $quoteTransfer,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime())->sub(new DateInterval("PT1H"))->format('Y-m-d H:i:s'),
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findQuoteRequestTransfer')
            ->willReturn($quoteRequestTransfer);

        $quoteRequestCriteriaTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestCreatesUserQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::COMPANY_USER => $this->companyUserTransfer,
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findCustomerReference')
            ->willReturn($this->companyUserTransfer->getCustomer()->getCustomerReference());

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->createQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertTrue($storedQuoteRequestTransfer->getIsHidden());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_IN_PROGRESS, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestWithoutIdCompanyUser(): void
    {
        // Arrange

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->userQuoteRequestWriter->createQuoteRequest(new QuoteRequestTransfer());
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestWithoutCustomer(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::COMPANY_USER => $this->companyUserTransfer,
        ]))->build();

        $this->userQuoteRequestWriter->expects($this->any())
            ->method('findCustomerReference')
            ->willReturn(null);

        // Act
        $quoteRequestResponseTransfer = $this->userQuoteRequestWriter->createQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $quoteRequestResponseTransfer->getMessages());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createUserQuoteRequestWriterMock(): MockObject
    {
        $userQuoteRequestWriterMock = $this->getMockBuilder(UserQuoteRequestWriter::class)
            ->setMethods(['findQuoteRequestTransfer', 'findCustomerReference', 'addQuoteRequestVersion'])
            ->setConstructorArgs([
                $this->createQuoteRequestConfigMock(),
                $this->createQuoteRequestEntityManagerInterfaceMock(),
                $this->createQuoteRequestRepositoryInterfaceMock(),
                $this->createQuoteRequestReferenceGeneratorInterfaceMock(),
                $this->createQuoteRequestToCompanyUserInterfaceMock(),
            ])
            ->getMock();

        $userQuoteRequestWriterMock
            ->method('addQuoteRequestVersion')
            ->willReturnCallback(function (QuoteRequestTransfer $quoteRequestTransfer) {
                return (new QuoteRequestVersionTransfer())->setQuote($quoteRequestTransfer->getQuoteInProgress());
            });

        return $userQuoteRequestWriterMock;
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
