<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group QuoteRequestCheckerTest
 * Add your own group annotations below this line
 */
class QuoteRequestCheckerTest extends Unit
{
    protected const FAKE_ID_QUOTE_REQUEST_VERSION = 'FAKE_ID_QUOTE_REQUEST_VERSION';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND = 'quote_request.checkout.validation.error.not_found';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION = 'quote_request.checkout.validation.error.wrong_version';

    /**
     * @uses \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL
     */
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRequestCheckerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteRequestCheckerMock = $this->createQuoteRequestChecker();
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestSuccessfully(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_READY,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime())->add(new DateInterval("PT1H"))->format('Y-m-d H:i:s'),
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => $quoteRequestVersionTransfer->getVersionReference(),
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertNull($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWithoutQuoteRequestVersionReference(): void
    {
        // Arrange

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil(
            (new QuoteBuilder())->build(),
            new CheckoutResponseTransfer()
        );

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestVersionNotFound(): void
    {
        // Arrange
        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn(null);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => static::FAKE_ID_QUOTE_REQUEST_VERSION,
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn(null);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => static::FAKE_ID_QUOTE_REQUEST_VERSION,
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestWithWrongStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => $quoteRequestVersionTransfer->getVersionReference(),
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestVersionNotLast(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_READY,
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder())->build();
        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::ID_QUOTE_REQUEST_VERSION => static::FAKE_ID_QUOTE_REQUEST_VERSION,
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => $quoteRequestVersionTransfer->getVersionReference(),
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestWithEmptyValidUntil(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_READY,
            QuoteRequestTransfer::VALID_UNTIL => null,
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => $quoteRequestVersionTransfer->getVersionReference(),
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testCheckValidUntilValidatesQuoteWhenQuoteRequestWithWrongValidUntil(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::STATUS => SharedQuoteRequestConfig::STATUS_READY,
            QuoteRequestTransfer::VALID_UNTIL => (new DateTime())->sub(new DateInterval("PT1H"))->format('Y-m-d H:i:s'),
        ]))->build();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder([
            QuoteRequestVersionTransfer::QUOTE_REQUEST => $quoteRequestTransfer,
        ]))->build();

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequest')
            ->willReturn($quoteRequestTransfer);

        $this->quoteRequestCheckerMock->expects($this->any())
            ->method('findQuoteRequestVersion')
            ->willReturn($quoteRequestVersionTransfer);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE => $quoteRequestVersionTransfer->getVersionReference(),
        ]))->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->quoteRequestCheckerMock->checkValidUntil($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestChecker(): MockObject
    {
        $quoteRequestChecker = $this->getMockBuilder(QuoteRequestChecker::class)
            ->setMethods(['findQuoteRequest', 'findQuoteRequestVersion'])
            ->setConstructorArgs([$this->createQuoteRequestRepositoryMock()])
            ->getMock();

        return $quoteRequestChecker;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteRequestRepositoryMock(): MockObject
    {
        return $this->getMockBuilder(QuoteRequestRepositoryInterface::class)
            ->setMethods(['getQuoteRequestCollectionByFilter', 'getQuoteRequestVersionCollectionByFilter'])
            ->disableOriginalConstructor()
            ->getMock();
    }
}
