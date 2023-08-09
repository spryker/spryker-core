<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\ServicePointCart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @var \SprykerTest\Client\ServicePointCart\ServicePointCartClientTester
     */
    protected ServicePointCartClientTester $tester;

    /**
     * @return void
     */
    public function testReplaceQuoteItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_QUOTE,
            $this->createQuoteClientMock(),
        );
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_ZED_REQUEST,
            $this->createZedRequestClientMock($quoteTransfer),
        );

        // Act
        $quoteResponseTransfer = $this->tester->getClient()->replaceQuoteItems($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface
     */
    protected function createZedRequestClientMock(QuoteTransfer $quoteTransfer): ServicePointCartToZedRequestClientInterface
    {
        $zedRequestClientMock = $this->getMockBuilder(ServicePointCartToZedRequestClientInterface::class)
            ->getMock();
        $zedRequestClientMock->method('call')
            ->willReturn((new QuoteResponseTransfer())->setQuoteTransfer($quoteTransfer));

        return $zedRequestClientMock;
    }
}
