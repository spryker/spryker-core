<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityCartConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Availability\Communication\Plugin\Cart\ProductConcreteBatchAvailabilityStrategyPlugin;
use SprykerTest\Zed\AvailabilityCartConnector\AvailabilityCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityCartConnector
 * @group Business
 * @group Facade
 * @group FilterOutUnavailableCartChangeItemsTest
 * Add your own group annotations below this line
 */
class FilterOutUnavailableCartChangeItemsTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Availability\AvailabilityDependencyProvider::PLUGINS_BATCH_AVAILABILITY_STRATEGY
     *
     * @var string
     */
    public const PLUGINS_BATCH_AVAILABILITY_STRATEGY = 'PLUGINS_BATCH_AVAILABILITY_STRATEGY';

    /**
     * @var \SprykerTest\Zed\AvailabilityCartConnector\AvailabilityCartConnectorBusinessTester
     */
    protected AvailabilityCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            static::PLUGINS_BATCH_AVAILABILITY_STRATEGY,
            [new ProductConcreteBatchAvailabilityStrategyPlugin()],
        );
    }

    /**
     * @return void
     */
    public function testRemovesUnavailableItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $availableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 10);
        $unavailableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 0);

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => $storeTransfer->toArray()])
            ->withItem([
                ItemTransfer::SKU => $availableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])->withAnotherItem([
                ItemTransfer::SKU => $unavailableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])->build();

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterOutUnavailableCartChangeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(1, $cartChangeTransfer->getItems());
        $this->assertSame(
            $availableProductConcreteTransfer->getSkuOrFail(),
            $cartChangeTransfer->getItems()->offsetGet(0)->getSku(),
        );
    }

    /**
     * @return void
     */
    public function testChangesQuantityForUnavailableItem(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $availableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 10);
        $notFullyAvailableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 1);

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => $storeTransfer->toArray()])
            ->withItem([
                ItemTransfer::SKU => $availableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 3,
            ])->withAnotherItem([
                ItemTransfer::SKU => $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 2,
            ])->build();

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterOutUnavailableCartChangeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(2, $cartChangeTransfer->getItems());
        $this->assertSame(
            $availableProductConcreteTransfer->getSkuOrFail(),
            $cartChangeTransfer->getItems()->offsetGet(0)->getSku(),
        );
        $this->assertSame(3, $cartChangeTransfer->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(
            $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
            $cartChangeTransfer->getItems()->offsetGet(1)->getSku(),
        );
        $this->assertSame(1, $cartChangeTransfer->getItems()->offsetGet(1)->getQuantity());
    }

    /**
     * @return void
     */
    public function testRemovesDuplicatedUnavailableItem(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $availableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 10);
        $notFullyAvailableProductConcreteTransfer = $this->tester->createProductWithAvailabilityForStore($storeTransfer, 1);

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => $storeTransfer->toArray()])
            ->withItem([
                ItemTransfer::SKU => $availableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 3,
            ])->withAnotherItem([
                ItemTransfer::SKU => $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])->withAnotherItem([
                ItemTransfer::SKU => $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])->withAnotherItem([
                ItemTransfer::SKU => $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])->build();

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->filterOutUnavailableCartChangeItems($cartChangeTransfer);

        // Assert
        $this->assertCount(2, $cartChangeTransfer->getItems());
        $this->assertSame(
            $availableProductConcreteTransfer->getSkuOrFail(),
            $cartChangeTransfer->getItems()->offsetGet(0)->getSku(),
        );
        $this->assertSame(3, $cartChangeTransfer->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(
            $notFullyAvailableProductConcreteTransfer->getSkuOrFail(),
            $cartChangeTransfer->getItems()->offsetGet(1)->getSku(),
        );
        $this->assertSame(1, $cartChangeTransfer->getItems()->offsetGet(1)->getQuantity());
    }

    /**
     * @return void
     */
    public function testThrowsRequiredTransferPropertyExceptionWhenStoreIsNotProvided(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote([QuoteTransfer::STORE => null])
            ->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(sprintf('Missing required property "store" for transfer %s.', QuoteTransfer::class));

        // Act
        $this->tester->getFacade()->filterOutUnavailableCartChangeItems($cartChangeTransfer);
    }
}
