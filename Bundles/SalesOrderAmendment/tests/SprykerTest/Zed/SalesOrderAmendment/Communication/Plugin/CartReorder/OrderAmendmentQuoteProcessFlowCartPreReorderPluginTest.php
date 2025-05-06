<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use RuntimeException;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\AmendmentOrderReferenceCartPreReorderPlugin;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToQuoteFacadeInterface;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group OrderAmendmentQuoteProcessFlowCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentQuoteProcessFlowCartPreReorderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_SESSION = 'session';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldSetOrderAmendmentQuoteProcessFlowWhenIsAmendmentFlagIsSetToTrue(): void
    {
        // Arrange
        $this->mockQuoteFacade(static::STORAGE_STRATEGY_DATABASE);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNotNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
        $this->assertSame(
            SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT,
            $cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlowOrFail()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentFlagIsSetToFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(false);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentFlagIsNotSet(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(null);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = new CartReorderTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new AmendmentOrderReferenceCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowRuntimeExceptionWhenQuoteStorageStrategyIsSession(): void
    {
        // Arrange
        $this->mockQuoteFacade(static::STORAGE_STRATEGY_SESSION);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The session storage strategy is not supported for the order amendment process flow.');

        // Act
        (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @param string $storageStrategy
     *
     * @return void
     */
    protected function mockQuoteFacade(string $storageStrategy): void
    {
        $quoteFacadeMock = $this->getMockBuilder(SalesOrderAmendmentToQuoteFacadeInterface::class)->getMock();
        $quoteFacadeMock->method('getStorageStrategy')->willReturn($storageStrategy);

        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::FACADE_QUOTE,
            $quoteFacadeMock,
        );
    }
}
