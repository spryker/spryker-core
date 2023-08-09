<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointCart\Business\Facade;

use Codeception\Test\Unit;
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
    public function testExecutesApplicableStrategyPlugins(): void
    {
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock()],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock(true),
        );

        $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testSkipsNonApplicableStrategyPlugins(): void
    {
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock(false)],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock(true),
        );

        $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testApplicableStrategyPluginsNotSuccessful(): void
    {
        $this->tester->setDependency(
            ServicePointCartDependencyProvider::PLUGINS_SERVICE_POINT_QUOTE_ITEM_REPLACE_STRATEGY,
            [$this->createServicePointQuoteItemReplaceStrategyPluginMock(true, false)],
        );

        $this->tester->setDependency(
            ServicePointCartDependencyProvider::FACADE_CART,
            $this->createCartFacadeMock(false),
        );

        $this->tester->getFacade()->replaceQuoteItems(new QuoteTransfer());
    }

    /**
     * @param bool $isApplicable
     * @param bool $isSuccessful
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface
     */
    protected function createServicePointQuoteItemReplaceStrategyPluginMock(
        bool $isApplicable = true,
        bool $isSuccessful = true
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
                (new QuoteResponseTransfer())
                    ->setQuoteTransfer(new QuoteTransfer())
                    ->setIsSuccessful($isSuccessful),
            );

        return $servicePointQuoteItemReplaceStrategyPluginMock;
    }

    /**
     * @param bool $shouldExecute
     *
     * @return \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface
     */
    protected function createCartFacadeMock(bool $shouldExecute): ServicePointCartToCartFacadeInterface
    {
        $cartFacadeMock = $this->getMockBuilder(ServicePointCartToCartFacadeInterface::class)
            ->getMock();

        $cartFacadeMock
            ->expects($shouldExecute ? $this->once() : $this->never())
            ->method('reloadItems');

        return $cartFacadeMock;
    }
}
