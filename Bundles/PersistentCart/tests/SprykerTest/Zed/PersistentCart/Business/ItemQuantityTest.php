<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Business
 * @group ItemQuantityTest
 * Add your own group annotations below this line
 */
class ItemQuantityTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider increaseItemQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int|float $addQuantity
     * @param int|float $resultQuantity
     *
     * @return void
     */
    public function testIncreaseItemQuantity(ItemTransfer $itemTransfer, $addQuantity, $resultQuantity): void
    {
        $quoteTransfer = $this->createPersistentQuote($itemTransfer);

        $itemTransfer->setQuantity($addQuantity);

        $persistentCartChangeQuantityTransfer = new PersistentCartChangeQuantityTransfer();
        $persistentCartChangeQuantityTransfer->setCustomer($quoteTransfer->getCustomer());
        $persistentCartChangeQuantityTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $persistentCartChangeQuantityTransfer->setItem($itemTransfer);

        $resultQuoteTransfer = $this->getFacade()
            ->increaseItemQuantity($persistentCartChangeQuantityTransfer)
            ->getQuoteTransfer();

        $this->assertEquals($resultQuantity, $resultQuoteTransfer->getItems()[0]->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createPersistentQuote(ItemTransfer $itemTransfer)
    {
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::ITEMS => [
                $itemTransfer->toArray(),
            ],
        ]);

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    public function increaseItemQuantityDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForIncreaseItemQuantity(1, 2, 3),
            'float stock' => $this->getDataForIncreaseItemQuantity(1.1, 2.0023, 3.1023),
        ];
    }

    /**
     * @param int|float $quoteItemQuantity
     * @param int|float $addQuantity
     * @param int|float $resultQuantity
     *
     * @return array
     */
    protected function getDataForIncreaseItemQuantity($quoteItemQuantity, $addQuantity, $resultQuantity): array
    {
        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::QUANTITY => $quoteItemQuantity,
            ItemTransfer::SKU => 'test-sku',
        ])->build();

        return [$itemTransfer, $addQuantity, $resultQuantity];
    }

    /**
     * @dataProvider decreaseItemQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int|float $subtractQuantity
     * @param int|float $resultQuantity
     *
     * @return void
     */
    public function testDecreaseItemQuantity(ItemTransfer $itemTransfer, $subtractQuantity, $resultQuantity)
    {
        $quoteTransfer = $this->createPersistentQuote($itemTransfer);

        $itemTransfer->setQuantity($subtractQuantity);

        $persistentCartChangeQuantityTransfer = new PersistentCartChangeQuantityTransfer();
        $persistentCartChangeQuantityTransfer->setCustomer($quoteTransfer->getCustomer());
        $persistentCartChangeQuantityTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $persistentCartChangeQuantityTransfer->setItem($itemTransfer);

        $resultQuote = $this->getFacade()
            ->decreaseItemQuantity($persistentCartChangeQuantityTransfer)
            ->getQuoteTransfer();

        $this->assertEquals($resultQuantity, $resultQuote->getItems()[0]->getQuantity());
    }

    /**
     * @return array
     */
    public function decreaseItemQuantityDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForDecreaseItemQuantity(3, 1, 2),
            'float stock' => $this->getDataForDecreaseItemQuantity(3.1023, 2.0023, 1.1),
        ];
    }

    /**
     * @param int|float $quoteItemQuantity
     * @param int|float $subtractQuantity
     * @param int|float $resultQuantity
     *
     * @return array
     */
    protected function getDataForDecreaseItemQuantity($quoteItemQuantity, $subtractQuantity, $resultQuantity): array
    {
        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::QUANTITY => $quoteItemQuantity,
            ItemTransfer::SKU => 'test-sku',
        ])->build();

        return [$itemTransfer, $subtractQuantity, $resultQuantity];
    }

    /**
     * @dataProvider changeItemQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int|float $changedQuantity
     *
     * @return void
     */
    public function testChangeItemQuantity(ItemTransfer $itemTransfer, $changedQuantity)
    {
        $quoteTransfer = $this->createPersistentQuote($itemTransfer);

        $itemTransfer->setQuantity($changedQuantity);

        $persistentCartChangeQuantityTransfer = new PersistentCartChangeQuantityTransfer();
        $persistentCartChangeQuantityTransfer->setCustomer($quoteTransfer->getCustomer());
        $persistentCartChangeQuantityTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $persistentCartChangeQuantityTransfer->setItem($itemTransfer);

        $resultQuote = $this->getFacade()
            ->changeItemQuantity($persistentCartChangeQuantityTransfer)
            ->getQuoteTransfer();

        $this->assertEquals($changedQuantity, $resultQuote->getItems()[0]->getQuantity());
    }

    /**
     * @return array
     */
    public function changeItemQuantityDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForChangeItemQuantity(3, 2),
            'float stock' => $this->getDataForChangeItemQuantity(3.1023, 2.0023),
        ];
    }

    /**
     * @param int|float $quoteItemQuantity
     * @param int|float $changedQuantity
     *
     * @return array
     */
    protected function getDataForChangeItemQuantity($quoteItemQuantity, $changedQuantity): array
    {
        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::QUANTITY => $quoteItemQuantity,
            ItemTransfer::SKU => 'test-sku',
        ])->build();

        return [$itemTransfer, $changedQuantity];
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface
     */
    protected function getFacade(): PersistentCartFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
