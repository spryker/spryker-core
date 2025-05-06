<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiCart\Communication\Plugin\CartReorder;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\MultiCart\Communication\Plugin\CartReorder\NewPersistentCartReorderQuoteProviderStrategyPlugin;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;
use Spryker\Zed\MultiCart\MultiCartDependencyProvider;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;
use SprykerTest\Zed\MultiCart\MultiCartCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiCart
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group NewPersistentCartReorderQuoteProviderStrategyPluginTest
 * Add your own group annotations below this line
 */
class NewPersistentCartReorderQuoteProviderStrategyPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_QUOTE_NAME = 'FAKE_QUOTE_NAME';

    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'FAKE_ORDER_REFERENCE';

    /**
     * @uses \Spryker\Zed\MultiCart\Communication\Plugin\CartReorder\NewPersistentCartReorderQuoteProviderStrategyPlugin::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @uses \Spryker\Zed\MultiCart\Communication\Plugin\CartReorder\NewPersistentCartReorderQuoteProviderStrategyPlugin::REORDER_STRATEGY_NEW
     *
     * @var string
     */
    protected const REORDER_STRATEGY_NEW = 'new';

    /**
     * @var \SprykerTest\Zed\MultiCart\MultiCartCommunicationTester
     */
    protected MultiCartCommunicationTester $tester;

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnFalseWhenReorderStrategyIsNotNew(): void
    {
        // Act
        $this->mockQuoteFacade(static::STORAGE_STRATEGY_DATABASE);
        $isApplicable = (new NewPersistentCartReorderQuoteProviderStrategyPlugin())
            ->isApplicable((new CartReorderRequestTransfer())
            ->setReorderStrategy('FAKE_STRATEGY'));

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnFalseWhenStorageStrategyIsNotDatabase(): void
    {
        // Act
        $this->mockQuoteFacade('session');
        $isApplicable = (new NewPersistentCartReorderQuoteProviderStrategyPlugin())
            ->isApplicable((new CartReorderRequestTransfer())->setReorderStrategy(static::REORDER_STRATEGY_NEW));

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnTrueWhenStorageStrategyIsDatabaseAndReorderStrategyIsNew(): void
    {
        // Act
        $this->mockQuoteFacade(static::STORAGE_STRATEGY_DATABASE);
        $isApplicable = (new NewPersistentCartReorderQuoteProviderStrategyPlugin())
            ->isApplicable((new CartReorderRequestTransfer())->setReorderStrategy(static::REORDER_STRATEGY_NEW));

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testExecuteShouldThrowExceptionWhenCustomerReferenceNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customerReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        (new NewPersistentCartReorderQuoteProviderStrategyPlugin())->execute(new CartReorderRequestTransfer());
    }

    /**
     * @return void
     */
    public function testExecuteShouldCreateNewCustomerQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());
        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_BEFORE,
            [$this->getAddDefaultNameBeforeQuoteSavePluginMock()],
        );

        // Act
        $quoteTransfer = (new NewPersistentCartReorderQuoteProviderStrategyPlugin())->execute($cartReorderRequestTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer);
        $this->assertSame(static::FAKE_QUOTE_NAME, $quoteTransfer->getName());
    }

    /**
     * @param string $storageStrategy
     *
     * @return void
     */
    protected function mockQuoteFacade(string $storageStrategy): void
    {
        $quoteFacadeMock = $this->getMockBuilder(MultiCartToQuoteFacadeInterface::class)->getMock();
        $quoteFacadeMock->method('getStorageStrategy')->willReturn($storageStrategy);

        $this->tester->setDependency(
            MultiCartDependencyProvider::FACADE_QUOTE,
            $quoteFacadeMock,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface
     */
    protected function getAddDefaultNameBeforeQuoteSavePluginMock(): QuoteWritePluginInterface
    {
        return Stub::makeEmpty(QuoteWritePluginInterface::class, [
            'execute' => function (QuoteTransfer $quoteTransfer) {
                $quoteTransfer->setName(static::FAKE_QUOTE_NAME);

                return $quoteTransfer;
            },
        ]);
    }
}
