<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiCart\Business\MultiCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\MultiCartDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiCart
 * @group Business
 * @group MultiCartFacade
 * @group AddDefaultQuoteChangedMessageTest
 * Add your own group annotations below this line
 */
class AddDefaultQuoteChangedMessageTest extends Unit
{
    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_1 = 'customer-reference-1';

    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE_2 = 'customer-reference-2';

    /**
     * @var \SprykerTest\Zed\MultiCart\MultiCartBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider quoteOwnerCasesDataProvider
     *
     * @param bool $isDefaultQuote
     * @param bool $isMessageExpected
     *
     * @return void
     */
    public function testMessagesForQuoteOwner(bool $isDefaultQuote, bool $isMessageExpected): void
    {
        // Arrange
        $multiCartToMessengerFacadeMock = $this->setupMultiCartToMessengerFacadeMock();
        $quoteTransfer = $this->setupQuote(
            static::CUSTOMER_REFERENCE_1,
            $isDefaultQuote,
            static::CUSTOMER_REFERENCE_1,
        );

        // Assert
        $multiCartToMessengerFacadeMock
            ->expects($isMessageExpected ? $this->once() : $this->never())
            ->method('addInfoMessage');

        // Act
        $this->tester->getFacade()->addDefaultQuoteChangedMessage($quoteTransfer);
    }

    /**
     * @dataProvider nonQuoteOwnerCasesDataProvider
     *
     * @param bool $isDefaultQuote
     * @param bool $isMessageExpected
     *
     * @return void
     */
    public function testMessagesForNonQuoteOwner(bool $isDefaultQuote, bool $isMessageExpected): void
    {
        // Arrange
        $multiCartToMessengerFacadeMock = $this->setupMultiCartToMessengerFacadeMock();
        $quoteTransfer = $this->setupQuote(
            static::CUSTOMER_REFERENCE_1,
            $isDefaultQuote,
            static::CUSTOMER_REFERENCE_2,
        );

        // Assert
        $multiCartToMessengerFacadeMock
            ->expects($isMessageExpected ? $this->once() : $this->never())
            ->method('addInfoMessage');

        // Act
        $this->tester->getFacade()->addDefaultQuoteChangedMessage($quoteTransfer);
    }

    /**
     * @return array<array<bool>>
     */
    public function quoteOwnerCasesDataProvider(): array
    {
        return [
            'Test that message is added when quote is not default' => [false, true],
            'Test that message is not added when quote is default' => [true, false],
        ];
    }

    /**
     * @return array<array<bool>>
     */
    public function nonQuoteOwnerCasesDataProvider(): array
    {
        return [
            'Test that message is not added when quote is not default for its owner' => [false, false],
            'Test that message is not added when quote is default for its owner' => [true, false],
        ];
    }

    /**
     * @param string $quoteOwnerCustomerReference
     * @param bool $isDefaultQuote
     * @param string $currentUserCustomerReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setupQuote(string $quoteOwnerCustomerReference, bool $isDefaultQuote, string $currentUserCustomerReference): QuoteTransfer
    {
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->buildCustomerTransfer($quoteOwnerCustomerReference),
            QuoteTransfer::IS_DEFAULT => $isDefaultQuote,
        ]);

        if ($quoteOwnerCustomerReference !== $currentUserCustomerReference) {
            $quoteTransfer->setCustomer($this->buildCustomerTransfer($currentUserCustomerReference));
        }

        return $quoteTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function buildCustomerTransfer(string $customerReference): CustomerTransfer
    {
        return (new CustomerBuilder())->build()
            ->setCustomerReference($customerReference);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected function setupMultiCartToMessengerFacadeMock(): MultiCartToMessengerFacadeInterface
    {
        $multiCartToMessengerFacadeMock = $this
            ->getMockBuilder(MultiCartToMessengerFacadeInterface::class)
            ->getMock();

        $this->tester->setDependency(MultiCartDependencyProvider::FACADE_MESSENGER, $multiCartToMessengerFacadeMock);

        return $multiCartToMessengerFacadeMock;
    }
}
