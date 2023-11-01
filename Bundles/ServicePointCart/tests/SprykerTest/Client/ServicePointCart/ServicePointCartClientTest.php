<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\ServicePointCart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
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
    public function testReplaceQuoteItemsWithStrategyReturnsSameQuote(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::CLIENT_ZED_REQUEST,
            $this->createZedRequestClientMock((new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer)),
        );

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getClient()->replaceQuoteItems($quoteTransfer);

        // Assert
        $this->assertSame($quoteTransfer, $quoteReplacementResponseTransfer->getQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     *
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface
     */
    protected function createZedRequestClientMock(
        QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
    ): ServicePointCartToZedRequestClientInterface {
        $zedRequestClientMock = $this->getMockBuilder(ServicePointCartToZedRequestClientInterface::class)
            ->getMock();
        $zedRequestClientMock->method('call')
            ->willReturn($quoteReplacementResponseTransfer);

        return $zedRequestClientMock;
    }
}
