<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\ServicePointCart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToGlossaryStorageClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToLocaleClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToMessengerClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToQuoteClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface;
use Spryker\Client\ServicePointCart\ServicePointCartDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ServicePointCart
 * @group ServicePointCartClientTest
 * Add your own group annotations below this line
 */
class ServicePointCartClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MESSAGE = 'Test message - %test_param%';

    /**
     * @var string
     */
    protected const TEST_PARAMETER_KEY = '%test_param%';

    /**
     * @var string
     */
    protected const TEST_PARAMETER_VALUE = 'TestParam';

    /**
     * @var \SprykerTest\Client\ServicePointCart\ServicePointCartClientTester
     */
    protected ServicePointCartClientTester $tester;

    /**
     * @return void
     */
    public function testReplaceQuoteItemsWithStrategyReturnsNoErrors(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_QUOTE,
            $this->createQuoteClientMock(),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_ZED_REQUEST,
            $this->createZedRequestClientMock((new QuoteResponseTransfer())->setQuoteTransfer($quoteTransfer)),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_MESSENGER,
            $this->createMessengerClientMock(false),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_GLOSSARY_STORAGE,
            $this->createGlossaryStorageClientMock(false),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_LOCALE,
            $this->createLocaleClientMock(false),
        );

        // Act
        $quoteResponseTransfer = $this->tester->getClient()->replaceQuoteItems($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testReplaceQuoteItemsWithStrategyReturnErrors(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->addError(
            (new QuoteErrorTransfer())
                ->setMessage(static::TEST_MESSAGE)
                ->setParameters([
                    static::TEST_PARAMETER_KEY => static::TEST_PARAMETER_VALUE,
                ]),
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_QUOTE,
            $this->createQuoteClientMock(),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_ZED_REQUEST,
            $this->createZedRequestClientMock($quoteResponseTransfer),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_MESSENGER,
            $this->createMessengerClientMock(true),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_GLOSSARY_STORAGE,
            $this->createGlossaryStorageClientMock(true),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_LOCALE,
            $this->createLocaleClientMock(true),
        );

        // Act
        $quoteResponseTransfer = $this->tester->getClient()->replaceQuoteItems($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
    }

    /**
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToQuoteClientInterface
     */
    protected function createQuoteClientMock(): ServicePointCartToQuoteClientInterface
    {
        $cartClientMock = $this
            ->getMockBuilder(ServicePointCartToQuoteClientInterface::class)
            ->getMock();
        $cartClientMock->expects($this->once())->method('setQuote');

        return $cartClientMock;
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToLocaleClientInterface
     */
    protected function createLocaleClientMock(bool $shouldBeCalled): ServicePointCartToLocaleClientInterface
    {
        $cartClientMock = $this
            ->getMockBuilder(ServicePointCartToLocaleClientInterface::class)
            ->getMock();
        $cartClientMock
            ->expects($shouldBeCalled ? $this->once() : $this->never())
            ->method('getCurrentLocale');

        return $cartClientMock;
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToGlossaryStorageClientInterface
     */
    protected function createGlossaryStorageClientMock(bool $shouldBeCalled): ServicePointCartToGlossaryStorageClientInterface
    {
        $cartClientMock = $this
            ->getMockBuilder(ServicePointCartToGlossaryStorageClientInterface::class)
            ->getMock();
        $cartClientMock
            ->expects($shouldBeCalled ? $this->once() : $this->never())
            ->method('translate');

        return $cartClientMock;
    }

    /**
     * @param bool $shouldBeCalled
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToMessengerClientInterface
     */
    protected function createMessengerClientMock(bool $shouldBeCalled): ServicePointCartToMessengerClientInterface
    {
        $cartClientMock = $this
            ->getMockBuilder(ServicePointCartToMessengerClientInterface::class)
            ->getMock();
        $cartClientMock
            ->expects($shouldBeCalled ? $this->once() : $this->never())
            ->method('addErrorMessage');

        return $cartClientMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface
     */
    protected function createZedRequestClientMock(
        QuoteResponseTransfer $quoteResponseTransfer
    ): ServicePointCartToZedRequestClientInterface {
        $zedRequestClientMock = $this->getMockBuilder(ServicePointCartToZedRequestClientInterface::class)
            ->getMock();
        $zedRequestClientMock->method('call')
            ->willReturn($quoteResponseTransfer);

        return $zedRequestClientMock;
    }
}
