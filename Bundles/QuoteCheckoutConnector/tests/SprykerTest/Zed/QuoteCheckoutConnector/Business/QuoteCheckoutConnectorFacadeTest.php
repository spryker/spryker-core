<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteCheckoutConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\QuoteCheckoutConnector\Dependency\Client\QuoteCheckoutConnectorToStorageRedisClientInterface;
use Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorDependencyProvider;
use SprykerTest\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteCheckoutConnector
 * @group Business
 * @group Facade
 * @group QuoteCheckoutConnectorFacadeTest
 * Add your own group annotations below this line
 */
class QuoteCheckoutConnectorFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCK_STORAGE_KEY_PATTERN = 'quote:checkout:lock:%s';

    /**
     * @var string
     */
    protected const LOCK_STORAGE_KEY_UUID = 'test_uuid';

    /**
     * @var \SprykerTest\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorBusinessTester
     */
    protected QuoteCheckoutConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testDisallowCheckoutForQuoteThrowsException(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->disallowCheckoutForQuote(new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testDisallowCheckoutForQuoteAddsLockEntityToTheStorage(): void
    {
        // Arrange
        $quoteCheckoutConnectorToStorageRedisClientMock = $this->createMock(QuoteCheckoutConnectorToStorageRedisClientInterface::class);
        $this->tester->setDependency(QuoteCheckoutConnectorDependencyProvider::CLIENT_STORAGE_REDIS, $quoteCheckoutConnectorToStorageRedisClientMock);
        $quoteTransfer = (new QuoteTransfer())->setUuid(static::LOCK_STORAGE_KEY_UUID);

        // Act
        $resultQuoteTransfer = $this->tester->getFacade()->disallowCheckoutForQuote($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $resultQuoteTransfer);
        $this->assertInstanceOf(QuoteTransfer::class, $resultQuoteTransfer);
        $quoteCheckoutConnectorToStorageRedisClientMock->method('set')->with(
            $this->equalTo(sprintf(static::LOCK_STORAGE_KEY_PATTERN, static::LOCK_STORAGE_KEY_UUID)),
        )->willReturn(null);
    }

    /**
     * @return void
     */
    public function testDisallowCheckoutForQuoteAddsLockEntityToTheStorageUsesCustomerData(): void
    {
        // Arrange
        $quoteCheckoutConnectorToStorageRedisClientMock = $this->createMock(QuoteCheckoutConnectorToStorageRedisClientInterface::class);
        $this->tester->setDependency(QuoteCheckoutConnectorDependencyProvider::CLIENT_STORAGE_REDIS, $quoteCheckoutConnectorToStorageRedisClientMock);
        $quoteTransfer = (new QuoteTransfer())->setCustomer(
            (new CustomerTransfer())->setFirstName('Spryker')->setLastName('oscar')->setEmail('oscar@spryker.com'),
        );

        // Act
        $resultQuoteTransfer = $this->tester->getFacade()->disallowCheckoutForQuote($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $resultQuoteTransfer);
        $this->assertInstanceOf(QuoteTransfer::class, $resultQuoteTransfer);
        $quoteCheckoutConnectorToStorageRedisClientMock->method('set')->with(
            $this->equalTo('quote:checkout:lock:6a5296f8c258dc97c8fa7697812fdf46'),
        )->willReturn(null);
    }

    /**
     * @return void
     */
    public function testIsCheckoutAllowedForQuoteReturnsTrue(): void
    {
        // Arrange
        $storageRedisClientMock = $this->createMock(QuoteCheckoutConnectorToStorageRedisClientInterface::class);
        $storageRedisClientMock->method('get')->with(
            $this->equalTo(sprintf(static::LOCK_STORAGE_KEY_PATTERN, static::LOCK_STORAGE_KEY_UUID)),
        )->willReturn(true);
        $this->tester->setDependency(QuoteCheckoutConnectorDependencyProvider::CLIENT_STORAGE_REDIS, $storageRedisClientMock);
        $quoteTransfer = (new QuoteTransfer())->setUuid(static::LOCK_STORAGE_KEY_UUID);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->isCheckoutAllowedForQuote($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testIsCheckoutAllowedForQuoteReturnsFalse(): void
    {
        // Arrange
        $storageRedisClientMock = $this->createMock(QuoteCheckoutConnectorToStorageRedisClientInterface::class);
        $storageRedisClientMock->method('get')->with(
            $this->equalTo(sprintf(static::LOCK_STORAGE_KEY_PATTERN, static::LOCK_STORAGE_KEY_UUID)),
        )->willReturn(null);
        $this->tester->setDependency(QuoteCheckoutConnectorDependencyProvider::CLIENT_STORAGE_REDIS, $storageRedisClientMock);
        $quoteTransfer = (new QuoteTransfer())->setUuid(static::LOCK_STORAGE_KEY_UUID);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->isCheckoutAllowedForQuote($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertNull($checkoutResponseTransfer->getIsSuccess());
    }
}
