<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMerger;
use Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Business
 * @group Model
 * @group QuoteMergerTest
 * Add your own group annotations below this line
 */
class QuoteMergerTest extends Unit
{
    protected const EXISTING_ITEM_SKU = 'sku_1';
    protected const NEW_ITEM_SKU = 'sku_2';

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface
     */
    private $cartMerger;

    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $factory = $this->createPersistentCartBusinessFactoryMock();
        $this->cartMerger = new QuoteMerger(
            $factory->getCartAddItemStrategyPlugins()
        );
    }

    /**
     * @return void
     */
    public function testMergeSourceAndTargetQuote(): void
    {
        // Assign
        $quoteMergeRequestTransfer = $this->createQuoteMergeRequestTransfer();

        // Act
        $quoteTransfer = $this->cartMerger->merge($quoteMergeRequestTransfer);

        // Assert
        $changedItems = $quoteTransfer->getItems();
        $this->assertCount(2, $changedItems);
        $this->assertEquals($quoteTransfer->getCurrency()->getCode(), $quoteMergeRequestTransfer->getTargetQuote()->getCurrency()->getCode());

        $skuIndex = [];
        foreach ($changedItems as $key => $changedItem) {
            $skuIndex[$changedItem->getSku()] = $key;
        }

        $existingItem = $changedItems[$skuIndex[static::EXISTING_ITEM_SKU]];
        $this->assertEquals($existingItem->getQuantity(), 2);

        $newItem = $changedItems[$skuIndex[static::NEW_ITEM_SKU]];
        $this->assertEquals($newItem->getQuantity(), 1);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteMergeRequestTransfer
     */
    protected function createQuoteMergeRequestTransfer(): QuoteMergeRequestTransfer
    {
        return (new QuoteMergeRequestTransfer())
            ->setSourceQuote(
                (new QuoteTransfer())
                    ->addItem(
                        (new ItemTransfer())
                            ->setSku(static::EXISTING_ITEM_SKU)
                            ->setQuantity(1)
                    )->setCurrency(
                        (new CurrencyTransfer())
                            ->setCode('EUR')
                    )
            )
            ->setTargetQuote(
                (new QuoteTransfer())
                    ->addItem(
                        (new ItemTransfer())
                            ->setSku(static::EXISTING_ITEM_SKU)
                            ->setQuantity(1)
                    )
                    ->addItem(
                        (new ItemTransfer())
                            ->setSku(static::NEW_ITEM_SKU)
                            ->setQuantity(1)
                    )->setCurrency(
                        (new CurrencyTransfer())
                            ->setCode('USD')
                    )
            );
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory
     */
    protected function createPersistentCartBusinessFactoryMock(?MockObject $config = null): MockObject
    {
        $mockObject = $this->getMockBuilder(PersistentCartBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($config !== null) {
            $mockObject->setConfig($config);
        }

        return $mockObject;
    }
}
