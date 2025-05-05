<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Store\Communication\Plugin\CartReorder\CurrentStoreCartReorderValidatorPlugin;
use Spryker\Zed\Store\StoreDependencyProvider;
use SprykerTest\Zed\Store\StoreCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group CurrentStoreCartReorderValidatorPluginTest
 * Add your own group annotations below this line
 */
class CurrentStoreCartReorderValidatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\Communication\Plugin\CartReorder\CurrentStoreCartReorderValidatorPlugin::GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER
     *
     * @var string
     */
    protected const GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER = 'store.cart_reorder.error.store_mismatch';

    /**
     * @var \SprykerTest\Zed\Store\StoreCommunicationTester
     */
    protected StoreCommunicationTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
            'AT' => 'dev-AT',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldAddErrorToCartReorderResponseWhenCurrentStoreIsDifferent(): void
    {
        // Arrange
        $cartReorderTransfer = $this->createCartReorderTransfer('DE', 'DE');
        $this->tester->setDependency(StoreDependencyProvider::STORE_CURRENT, 'AT');

        // Act
        $cartReorderResponseTransfer = (new CurrentStoreCartReorderValidatorPlugin())
            ->validate($cartReorderTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER,
            $cartReorderResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldAddErrorToCartReorderResponseWhenQuoteStoreIsDifferent(): void
    {
        // Arrange
        $cartReorderTransfer = $this->createCartReorderTransfer('DE', 'AT');
        $this->tester->setDependency(StoreDependencyProvider::STORE_CURRENT, 'DE');

        // Act
        $cartReorderResponseTransfer = (new CurrentStoreCartReorderValidatorPlugin())
            ->validate($cartReorderTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER,
            $cartReorderResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldAddErrorToCartReorderResponseWhenOrderStoreIsDifferent(): void
    {
        // Arrange
        $cartReorderTransfer = $this->createCartReorderTransfer('AT', 'DE');
        $this->tester->setDependency(StoreDependencyProvider::STORE_CURRENT, 'DE');

        // Act
        $cartReorderResponseTransfer = (new CurrentStoreCartReorderValidatorPlugin())
            ->validate($cartReorderTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER,
            $cartReorderResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @dataProvider missingRequiredPropertiesProvider
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param string $expectedExceptionMessage
     *
     * @return void
     */
    public function testShouldThrowExceptionWhenRequiredPropertiesAreMissing(
        CartReorderTransfer $cartReorderTransfer,
        string $expectedExceptionMessage
    ): void {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Act
        (new CurrentStoreCartReorderValidatorPlugin())->validate($cartReorderTransfer, new CartReorderResponseTransfer());
    }

    /**
     * @return array<string, array>
     */
    public function missingRequiredPropertiesProvider(): array
    {
        return [
            'missing order' => [
                (new CartReorderTransfer())->setQuote(new QuoteTransfer()),
                'Property "order" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.',
            ],
            'missing order store' => [
                (new CartReorderTransfer())
                    ->setOrder(new OrderTransfer())
                    ->setQuote(new QuoteTransfer()),
                'Property "store" of transfer `Generated\Shared\Transfer\OrderTransfer` is null.',
            ],
            'missing quote' => [
                (new CartReorderTransfer())->setOrder((new OrderTransfer())->setStore('DE')),
                'Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.',
            ],
            'missing quote store' => [
                (new CartReorderTransfer())
                    ->setOrder((new OrderTransfer())->setStore('DE'))
                    ->setQuote(new QuoteTransfer()),
                'Property "store" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'missing quote store name' => [
                (new CartReorderTransfer())
                    ->setOrder((new OrderTransfer())->setStore('DE'))
                    ->setQuote((new QuoteTransfer())->setStore(new StoreTransfer())),
                'Property "name" of transfer `Generated\Shared\Transfer\StoreTransfer` is null.',
            ],
        ];
    }

    /**
     * @param string $orderStoreName
     * @param string $quoteStoreName
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    protected function createCartReorderTransfer(string $orderStoreName, string $quoteStoreName): CartReorderTransfer
    {
        $orderTransfer = (new OrderTransfer())->setStore($orderStoreName);
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName($quoteStoreName));

        return (new CartReorderTransfer())
            ->setOrder($orderTransfer)
            ->setQuote($quoteTransfer);
    }
}
