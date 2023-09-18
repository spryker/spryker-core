<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ProductOfferAvailability;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantStockAddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockAddressTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferAvailability\PHPMD)
 */
class ProductOfferAvailabilityCommunicationTester extends Actor
{
    use _generated\ProductOfferAvailabilityCommunicationTesterActions;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function haveStockWithStoreAssigned(StoreTransfer $storeTransfer): StockTransfer
    {
        return $this->haveStock([
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores(
                $storeTransfer->getIdStore(),
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function haveProductOfferWithStoreAssigned(StoreTransfer $storeTransfer): ProductOfferTransfer
    {
        return $this->haveProductOffer([
            ProductOfferTransfer::STORES => new ArrayObject([
                $storeTransfer,
            ]),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param int $quantity
     * @param bool $isNeverOutOfStock
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function haveProductOfferStockWithStockAndProductOfferAssigned(
        StockTransfer $stockTransfer,
        ProductOfferTransfer $productOfferTransfer,
        int $quantity = 1,
        bool $isNeverOutOfStock = false
    ): ProductOfferStockTransfer {
        return $this->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            ProductOfferStockTransfer::QUANTITY => $quantity,
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => $isNeverOutOfStock,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::NAME => $stockTransfer->getName(),
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    public function haveStockAddressRelatedToStock(StockTransfer $stockTransfer): StockAddressTransfer
    {
        return $this->haveStockAddress([
            StockAddressTransfer::ID_STOCK => $stockTransfer->getIdStock(),
            StockAddressTransfer::COUNTRY => $this->haveCountry(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function haveOrderWithOneItem(
        ProductOfferTransfer $productOfferTransfer,
        StoreTransfer $storeTransfer,
        int $quantity = 1
    ): OrderTransfer {
        return $this->haveOrderTransfer([
            OrderTransfer::STORE => $storeTransfer->getName(),
            OrderTransfer::ITEMS => [
                [
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::QUANTITY => new Decimal($quantity),
                ],
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function haveQuoteWithOneItem(
        ProductOfferTransfer $productOfferTransfer,
        StoreTransfer $storeTransfer,
        int $quantity = 1
    ): CalculableObjectTransfer {
        return $this->haveQuoteTransfer([
            CalculableObjectTransfer::STORE => $storeTransfer,
            CalculableObjectTransfer::ITEMS => [
                [
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::QUANTITY => new Decimal($quantity),
                ],
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $expandedOrderTransfer
     *
     * @return void
     */
    public function assertExpandedOrderTransferHasNoMerchantStockAddressHydrated(
        OrderTransfer $expandedOrderTransfer
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        $orderItemTransfer = $expandedOrderTransfer->getItems()->offsetGet(0);

        $this->assertEquals(
            0,
            $orderItemTransfer->getMerchantStockAddresses()->count(),
            'The MerchantStockAddresses must be empty in order item',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $expandedQuoteTransfer
     *
     * @return void
     */
    public function assertExpandedQuoteTransferHasNoMerchantStockAddressHydrated(
        CalculableObjectTransfer $expandedQuoteTransfer
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expandedQuoteTransfer->getItems()->offsetGet(0);

        $this->assertEquals(
            0,
            $quoteItemTransfer->getMerchantStockAddresses()->count(),
            'The MerchantStockAddresses must be empty in order item',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $mockedStockAddressTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $expandedOrderTransfer
     * @param \Spryker\DecimalObject\Decimal|null $quantityToShip
     *
     * @return void
     */
    public function assertExpandedOrderTransferHasOneMerchantStockAddressHydrated(
        StockAddressTransfer $mockedStockAddressTransfer,
        OrderTransfer $expandedOrderTransfer,
        ?Decimal $quantityToShip = null
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        $orderItemTransfer = $expandedOrderTransfer->getItems()->offsetGet(0);

        $this->assertNotNull(
            $orderItemTransfer->getMerchantStockAddresses(),
            'The MerchantStockAddresses must be provided in order item',
        );

        $orderItemMerchantStockAddressTransfer = $orderItemTransfer->getMerchantStockAddresses()->offsetGet(0);

        $this->assertItemIsHydratedWithMerchantStockAddress(
            $mockedStockAddressTransfer,
            $orderItemMerchantStockAddressTransfer,
            $quantityToShip,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $mockedStockAddressTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $expandedQuoteTransfer
     * @param \Spryker\DecimalObject\Decimal|null $quantityToShip
     *
     * @return void
     */
    public function assertExpandedQuoteTransferHasOneMerchantStockAddressHydrated(
        StockAddressTransfer $mockedStockAddressTransfer,
        CalculableObjectTransfer $expandedQuoteTransfer,
        ?Decimal $quantityToShip = null
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $quoteItemTransfer */
        $quoteItemTransfer = $expandedQuoteTransfer->getItems()->offsetGet(0);

        $this->assertNotNull(
            $quoteItemTransfer->getMerchantStockAddresses(),
            'The MerchantStockAddresses must be provided in order item',
        );

        $orderItemMerchantStockAddressTransfer = $quoteItemTransfer->getMerchantStockAddresses()->offsetGet(0);

        $this->assertItemIsHydratedWithMerchantStockAddress(
            $mockedStockAddressTransfer,
            $orderItemMerchantStockAddressTransfer,
            $quantityToShip,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $expandedOrderTransfer
     * @param \Generated\Shared\Transfer\StockAddressTransfer|array $mockedMerchantStockAddressesAndQuantityToShip
     *
     * @return void
     */
    public function assertExpandedOrderTransferHasMerchantStockAddressesHydratedWithRightOrdering(
        OrderTransfer $expandedOrderTransfer,
        array $mockedMerchantStockAddressesAndQuantityToShip
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        $orderItemTransfer = $expandedOrderTransfer->getItems()->offsetGet(0);

        foreach ($mockedMerchantStockAddressesAndQuantityToShip as $index => $mockedMerchantStockAddressAndQuantityToShip) {
            [
                'stock_address' => $mockedMerchantStockAddress,
                'quantity_to_ship' => $quantityToShip,
            ] = $mockedMerchantStockAddressAndQuantityToShip;

            $orderItemMerchantStockAddressTransfer = $orderItemTransfer->getMerchantStockAddresses()->offsetGet($index);

            $this->assertItemIsHydratedWithMerchantStockAddress(
                $mockedMerchantStockAddress,
                $orderItemMerchantStockAddressTransfer,
                $quantityToShip,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $expandedQuoteTransfer
     * @param array $mockedMerchantStockAddressesAndQuantityToShip
     *
     * @return void
     */
    public function assertExpandedQuoteTransferHasMerchantStockAddressesHydratedWithRightOrdering(
        CalculableObjectTransfer $expandedQuoteTransfer,
        array $mockedMerchantStockAddressesAndQuantityToShip
    ): void {
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        $orderItemTransfer = $expandedQuoteTransfer->getItems()->offsetGet(0);

        foreach ($mockedMerchantStockAddressesAndQuantityToShip as $index => $mockedMerchantStockAddressAndQuantityToShip) {
            [
                'stock_address' => $mockedMerchantStockAddress,
                'quantity_to_ship' => $quantityToShip,
            ] = $mockedMerchantStockAddressAndQuantityToShip;

            $orderItemMerchantStockAddressTransfer = $orderItemTransfer->getMerchantStockAddresses()->offsetGet($index);

            $this->assertItemIsHydratedWithMerchantStockAddress(
                $mockedMerchantStockAddress,
                $orderItemMerchantStockAddressTransfer,
                $quantityToShip,
            );
        }
    }

    /**
     * @param array<mixed> $seed
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function haveOrderTransfer(array $seed = []): OrderTransfer
    {
        $orderTransfer = (new OrderBuilder())->seed($seed)->build();

        if (!isset($seed['items']) || !is_array($seed['items'])) {
            return $orderTransfer;
        }

        return $orderTransfer->setItems($this->getItems($seed['items']));
    }

    /**
     * @param array<mixed> $seed
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function haveQuoteTransfer(array $seed = []): CalculableObjectTransfer
    {
        $calculableObjectTransfer = (new CalculableObjectBuilder())->seed($seed)->build();

        if (!isset($seed['items']) || !is_array($seed['items'])) {
            return $calculableObjectTransfer;
        }

        return $calculableObjectTransfer->setItems($this->getItems($seed['items']));
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $mockedMerchantStockAddress
     * @param \Generated\Shared\Transfer\MerchantStockAddressTransfer $itemMerchantStockAddressTransfer
     * @param \Spryker\DecimalObject\Decimal|null $quantityToShip
     *
     * @return void
     */
    protected function assertItemIsHydratedWithMerchantStockAddress(
        StockAddressTransfer $mockedMerchantStockAddress,
        MerchantStockAddressTransfer $itemMerchantStockAddressTransfer,
        ?Decimal $quantityToShip = null
    ): void {
        $this->assertEquals(
            $mockedMerchantStockAddress->getAddress1(),
            $itemMerchantStockAddressTransfer->getStockAddress()->getAddress1(),
            'The MerchantStockAddress must have its "address1" paramenter equals to the mocked one',
        );

        $this->assertEquals(
            $mockedMerchantStockAddress->getCity(),
            $itemMerchantStockAddressTransfer->getStockAddress()->getCity(),
            'The MerchantStockAddress must have its "city" paramenter equals to the mocked one',
        );

        $this->assertEquals(
            $mockedMerchantStockAddress->getZipCode(),
            $itemMerchantStockAddressTransfer->getStockAddress()->getZipCode(),
            'The MerchantStockAddress must have its "zipCode" paramenter equals to the mocked one',
        );

        $this->assertTrue(
            $quantityToShip->equals($itemMerchantStockAddressTransfer->getQuantityToShip()),
            'The quantity to ship in MerchantStockAddress is not the value expected.',
        );
    }

    /**
     * @param array<int, mixed> $seed
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItems(array $seed): ArrayObject
    {
        $items = [];

        foreach ($seed as $itemSeed) {
            $items[] = (new ItemBuilder())->seed($itemSeed)->build();
        }

        return new ArrayObject($items);
    }
}
