<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointCart\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface;
use Spryker\Zed\ServicePointCart\ServicePointCartDependencyProvider;
use Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface;
use SprykerTest\Zed\ServicePointCart\ServicePointCartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointCart
 * @group Business
 * @group Facade
 * @group ReplaceQuoteItemsTest
 * Add your own group annotations below this line
 */
class ReplaceQuoteItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointCart\ServicePointCartBusinessTester
     */
    protected ServicePointCartBusinessTester $tester;

    /**
     * @return void
     */
    public function testExecutesApplicableStrategyPlugin(): void
    {
        // Arrange
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setQuoteTransfer(new QuoteTransfer());
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock($quoteResponseTransfer->getQuoteTransfer())],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock($quoteResponseTransfer->getQuoteTransfer(), false),
        );

        $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testSkipsNonApplicableStrategyPlugins(): void
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setQuoteTransfer(new QuoteTransfer());
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock($quoteResponseTransfer->getQuoteTransfer())],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock($quoteResponseTransfer->getQuoteTransfer(), false),
        );

        $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testShouldReturnReloadedQuoteIfCartReloadItemsInQuoteIsSuccess(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setName('original');
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock($quoteTransfer)],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock($quoteTransfer, false),
        );

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());

        // Assert
        $this->assertNotSame($quoteTransfer->getNameOrFail(), $quoteReplacementResponseTransfer->getQuoteOrFail()->getNameOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnOriginalQuoteIfCartReloadItemsInQuoteFails(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setName('original');

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock($quoteTransfer)],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock($quoteTransfer, true),
        );

        // Act
        $quoteReplacementResponseTransfer = $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());

        // Assert
        $this->assertSame($quoteTransfer->getNameOrFail(), $quoteReplacementResponseTransfer->getQuoteOrFail()->getNameOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isApplicable
     *
     * @return \Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface
     */
    protected function createServicePointQuoteItemReplaceStrategyPluginMock(
        QuoteTransfer $quoteTransfer,
        bool $isApplicable = true
    ): ServicePointQuoteItemReplaceStrategyPluginInterface {
        $servicePointQuoteItemReplaceStrategyPluginMock = $this->getMockBuilder(ServicePointQuoteItemReplaceStrategyPluginInterface::class)
            ->getMock();

        $servicePointQuoteItemReplaceStrategyPluginMock->method('isApplicable')
            ->willReturn($isApplicable);

        $servicePointQuoteItemReplaceStrategyPluginMock
            ->expects($this->once())
            ->method('isApplicable');

        $servicePointQuoteItemReplaceStrategyPluginMock
            ->expects($isApplicable ? $this->once() : $this->never())
            ->method('execute')
            ->willReturn(
                (new QuoteReplacementResponseTransfer())
                    ->setQuote($quoteTransfer),
            );

        return $servicePointQuoteItemReplaceStrategyPluginMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $shouldFail
     *
     * @return \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface
     */
    protected function createCartFacadeMock(QuoteTransfer $quoteTransfer, bool $shouldFail): ServicePointCartToCartFacadeInterface
    {
        $cartFacadeMock = $this->getMockBuilder(ServicePointCartToCartFacadeInterface::class)
            ->getMock();

        $reloadedQuoteTransfer = (new QuoteTransfer())->setName('reloaded');
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setQuoteTransfer(
            $shouldFail ? $quoteTransfer : $reloadedQuoteTransfer,
        );

        $cartFacadeMock
            ->expects($this->once())
            ->method('reloadItemsInQuote')
            ->willReturn($quoteResponseTransfer->setIsSuccessful(!$shouldFail));

        return $cartFacadeMock;
    }
}
