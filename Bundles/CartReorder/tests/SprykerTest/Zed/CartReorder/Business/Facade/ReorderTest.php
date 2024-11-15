<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartReorder\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\CartReorder\CartReorderDependencyProvider;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemFilterPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface;
use SprykerTest\Zed\CartReorder\CartReorderBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartReorder
 * @group Business
 * @group Facade
 * @group ReorderTest
 * Add your own group annotations below this line
 */
class ReorderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CartReorder\Business\Creator\CartReorderCreator::GLOSSARY_KEY_ORDER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_NOT_FOUND = 'cart_reorder.validation.order_not_found';

    /**
     * @uses \Spryker\Zed\CartReorder\Business\Creator\CartReorderCreator::GLOSSARY_KEY_QUOTE_NOT_PROVIDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_NOT_PROVIDED = 'cart_reorder.validation.quote_not_provided';

    /**
     * @var \SprykerTest\Zed\CartReorder\CartReorderBusinessTester
     */
    protected CartReorderBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([CartReorderBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldReorderItemsToQuote(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder($this->tester->haveCustomer());
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->reorder($cartReorderRequestTransfer);

        // Assert
        $quoteTransfer = $cartReorderResponseTransfer->getQuote();
        $this->assertSame($orderTransfer->getItems()->count(), $quoteTransfer->getItems()->count());
    }

    /**
     * @return void
     */
    public function testShouldReorderConcreteItems(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder();
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->offsetGet(0);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(new QuoteTransfer())
            ->addIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->reorder($cartReorderRequestTransfer);

        // Assert
        $quoteTransfer = $cartReorderResponseTransfer->getQuote();
        $this->assertCount(1, $quoteTransfer->getItems());
        $this->assertSame($itemTransfer->getSku(), $quoteTransfer->getItems()->offsetGet(0)->getSku());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOrderReferenceNotSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setOrderReference(null)
            ->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "orderReference" for transfer Generated\Shared\Transfer\CartReorderRequestTransfer.');

        // Act
        $this->tester->getFacade()->reorder($cartReorderRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenCustomerReferenceNotSet(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference(null)
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "customerReference" for transfer Generated\Shared\Transfer\CartReorderRequestTransfer.');

        // Act
        $this->tester->getFacade()->reorder($cartReorderRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotReorderNonCustomerOrder(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrder();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->reorder($cartReorderRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_ORDER_NOT_FOUND,
            $cartReorderResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotReorderWithoutQuote(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrder();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(null);

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->reorder($cartReorderRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_QUOTE_NOT_PROVIDED,
            $cartReorderResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @dataProvider cartReorderPluginProvider
     *
     * @param string $dependencyKey
     * @param \PHPUnit\Framework\MockObject\MockObject $plugin
     *
     * @return void
     */
    public function testShouldExecutePluginStack(string $dependencyKey, MockObject $plugin): void
    {
        // Assert
        $this->tester->setDependency($dependencyKey, [$plugin]);

        // Arrange
        $orderTransfer = $this->tester->createOrder();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($orderTransfer->getCustomerReference())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setQuote(new QuoteTransfer());

        // Act
        $this->tester->getFacade()->reorder($cartReorderRequestTransfer);
    }

    /**
     * @return array<string, list<\PHPUnit\Framework\MockObject\MockObject>>
     */
    protected function cartReorderPluginProvider(): array
    {
        return [
            'quote provider plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY, $this->getCartReorderQuoteProviderStrategyPluginMock()],
            'filter plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_REORDER_ITEM_FILTER, $this->getCartReorderItemFilterPluginMock()],
            'validator plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_REORDER_VALIDATOR, $this->getCartReorderValidatorPluginMock()],
            'pre reorder plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_PRE_REORDER, $this->getCartPreReorderPluginMock()],
            'item hydrator plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_REORDER_ITEM_HYDRATOR, $this->getCartReorderItemHydratorPluginMock()],
            'pre add to cart plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_REORDER_PRE_ADD_TO_CART, $this->getCartReorderPreAddToCartPluginMock()],
            'post reorder plugin stack' => [CartReorderDependencyProvider::PLUGINS_CART_POST_REORDER, $this->getCartPostReorderPluginMock()],
        ];
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface
     */
    protected function getCartReorderQuoteProviderStrategyPluginMock(): CartReorderQuoteProviderStrategyPluginInterface
    {
        $cartReorderQuoteProviderStrategyPluginMock = $this
            ->getMockBuilder(CartReorderQuoteProviderStrategyPluginInterface::class)
            ->getMock();

        $cartReorderQuoteProviderStrategyPluginMock
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new QuoteTransfer());

        $cartReorderQuoteProviderStrategyPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        return $cartReorderQuoteProviderStrategyPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface
     */
    protected function getCartReorderValidatorPluginMock(): CartReorderValidatorPluginInterface
    {
        $cartReorderValidatorPluginMock = $this
            ->getMockBuilder(CartReorderValidatorPluginInterface::class)
            ->getMock();

        $cartReorderValidatorPluginMock
            ->expects($this->once())
            ->method('validate');

        return $cartReorderValidatorPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemFilterPluginInterface
     */
    protected function getCartReorderItemFilterPluginMock(): CartReorderItemFilterPluginInterface
    {
        $cartReorderItemFilterPluginMock = $this
            ->getMockBuilder(CartReorderItemFilterPluginInterface::class)
            ->getMock();

        $cartReorderItemFilterPluginMock
            ->expects($this->once())
            ->method('filter');

        return $cartReorderItemFilterPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface
     */
    protected function getCartPreReorderPluginMock(): CartPreReorderPluginInterface
    {
        $cartPreReorderPluginMock = $this
            ->getMockBuilder(CartPreReorderPluginInterface::class)
            ->getMock();

        $cartPreReorderPluginMock
            ->expects($this->once())
            ->method('preReorder')
            ->willReturnCallback(function (
                CartReorderRequestTransfer $cartReorderRequestTransfer,
                CartReorderTransfer $cartReorderTransfer
            ) {
                return $cartReorderTransfer;
            });

        return $cartPreReorderPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface
     */
    protected function getCartReorderItemHydratorPluginMock(): CartReorderItemHydratorPluginInterface
    {
        $cartReorderItemHydratorPluginMock = $this
            ->getMockBuilder(CartReorderItemHydratorPluginInterface::class)
            ->getMock();

        $cartReorderItemHydratorPluginMock
            ->expects($this->once())
            ->method('hydrate')
            ->willReturnCallback(function (CartReorderTransfer $cartReorderTransfer) {
                return $cartReorderTransfer;
            });

        return $cartReorderItemHydratorPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface
     */
    protected function getCartReorderPreAddToCartPluginMock(): CartReorderPreAddToCartPluginInterface
    {
        $cartReorderPreAddToCartPluginMock = $this
            ->getMockBuilder(CartReorderPreAddToCartPluginInterface::class)
            ->getMock();

        $cartReorderPreAddToCartPluginMock
            ->expects($this->once())
            ->method('preAddToCart')
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                return $cartChangeTransfer;
            });

        return $cartReorderPreAddToCartPluginMock;
    }

    /**
     * @return \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface
     */
    protected function getCartPostReorderPluginMock(): CartPostReorderPluginInterface
    {
        $cartPostReorderPluginMock = $this
            ->getMockBuilder(CartPostReorderPluginInterface::class)
            ->getMock();

        $cartPostReorderPluginMock
            ->expects($this->once())
            ->method('postReorder');

        return $cartPostReorderPluginMock;
    }
}
