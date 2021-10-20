<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsProcessTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsStateTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductPackagingUnitBusinessTester extends Actor
{
    use _generated\ProductPackagingUnitBusinessTesterActions;

    /**
     * @param int $amount
     * @param int $quantity
     * @param float $conversion
     * @param int $precision
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferForValueCalculation(int $amount, int $quantity, float $conversion, int $precision): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAmount($amount)
                ->setQuantity($quantity)
                ->setAmountSalesUnit(
                    (new ProductMeasurementSalesUnitTransfer())
                        ->setConversion($conversion)
                        ->setPrecision($precision),
                ));
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createEmptyCartChangeTransfer(): CartChangeTransfer
    {
        return (new CartChangeTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setItems(new ArrayObject([])),
            )
            ->setItems(new ArrayObject([]));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $boxProductConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param int $itemAmount
     * @param int $itemQuantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferForProductPackagingUnitValidation(
        ProductConcreteTransfer $boxProductConcreteTransfer,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        int $itemAmount,
        int $itemQuantity
    ): CartChangeTransfer {
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer()))
            ->addItem(
                (new ItemTransfer())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity($itemQuantity)
                    ->setAmount($itemAmount)
                    ->setAmountSalesUnit($productMeasurementSalesUnitTransfer)
                    ->setGroupKey(uniqid()),
            );

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addSkuToCartChangeTransfer(CartChangeTransfer $cartChangeTransfer, string $sku, int $amount): CartChangeTransfer
    {
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setAmount($amount),
        );

        return $cartChangeTransfer;
    }

    /**
     * @param string $sku
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithItem(string $sku, int $amount): CartChangeTransfer
    {
        return (new CartChangeTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setAmount($amount)
                ->setProductPackagingUnit(new ProductPackagingUnitTransfer()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $idProductPackagingUnit
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addProductPackagingUnitToCartChangeTransfer(
        CartChangeTransfer $cartChangeTransfer,
        string $sku,
        int $idProductPackagingUnit
    ): CartChangeTransfer {
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku($sku)
                ->setProductPackagingUnit((new ProductPackagingUnitTransfer())->setIdProductPackagingUnit($idProductPackagingUnit)),
        );

        return $cartChangeTransfer;
    }

    /**
     * @param string $dummyGroupKey
     * @param int $dummyAmount
     * @param int $dummyQuantity
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithountAmountSalesUnitForGroupKeyGeneration(
        string $dummyGroupKey,
        int $dummyAmount,
        int $dummyQuantity
    ): CartChangeTransfer {
        return (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setAmountSalesUnit(null)
                    ->setAmount($dummyAmount)
                    ->setQuantity($dummyQuantity)
                    ->setGroupKey($dummyGroupKey),
            );
    }

    /**
     * @param string $dummyGroupKey
     * @param int $dummyAmount
     * @param int $dummyQuantity
     * @param int $dummySalesUnitId
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithAmountSalesUnitForGroupKeyGeneration(
        string $dummyGroupKey,
        int $dummyAmount,
        int $dummyQuantity,
        int $dummySalesUnitId
    ): CartChangeTransfer {
        return (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setAmountSalesUnit((new ProductMeasurementSalesUnitTransfer())->setIdProductMeasurementSalesUnit($dummySalesUnitId))
                    ->setAmount($dummyAmount)
                    ->setQuantity($dummyQuantity)
                    ->setGroupKey($dummyGroupKey),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer());
    }

    /**
     * @param string $sku
     * @param int $itemQuantity
     * @param \Spryker\DecimalObject\Decimal $itemAmount
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createProductPackagingUnitItemTransfer(
        string $sku,
        int $itemQuantity,
        Decimal $itemAmount
    ): ItemTransfer {
        return (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($itemQuantity)
            ->setAmount($itemQuantity);
    }

    /**
     * @param int $quantity
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param int $itemsCount
     * @param bool $selfLead
     *
     * @return array
     */
    public function haveProductPackagingUnitWithSalesOrderItems(int $quantity, Decimal $amount, int $itemsCount, bool $selfLead = false): array
    {
        [$leadProductConcreteTransfer, $packagingUnitProductConcreteTransfer] = $this->havePackagingUnitAndLead($selfLead);

        $packagingUnitProductPackagingUnitType = $this->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => 'packagingUnit']);

        $this->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $leadProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $packagingUnitProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $packagingUnitProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => 1,
        ]);

        $stateCollectionTransfer = $this->haveSalesOrderWithItems(
            $itemsCount,
            $quantity,
            $packagingUnitProductConcreteTransfer->getSku(),
            $amount,
            $leadProductConcreteTransfer->getSku(),
        );

        return [
            $stateCollectionTransfer,
            $leadProductConcreteTransfer,
        ];
    }

    /**
     * @param int $itemsCount
     * @param int $quantity
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal|null $amount
     * @param string|null $amountSku
     *
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function haveSalesOrderWithItems(
        int $itemsCount,
        int $quantity,
        string $sku,
        ?Decimal $amount = null,
        ?string $amountSku = null
    ): OmsStateCollectionTransfer {
        $this->haveStore([StoreTransfer::NAME => 'DE']);
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $sku,
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::AMOUNT => $amount,
        ]))->build();
        $stateCollectionTransfer = new OmsStateCollectionTransfer();
        $salesOrderEntity = $this->haveSalesOrderEntity(array_fill(0, $itemsCount, $itemTransfer));

        $salesOrderEntity
            ->setStore('DE')
            ->save();

        foreach ($salesOrderEntity->getItems() as $orderItemEntity) {
            $orderItemEntity
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setAmountSku($amountSku)
                ->setAmount($amount)
                ->save();

            $stateName = $orderItemEntity->getState()->getName();
            $processName = $orderItemEntity->getProcess()->getName();
            $stateCollectionTransfer->addState(
                $stateName,
                (new OmsStateTransfer())
                    ->setName($stateName)
                    ->addProcess($processName, (new OmsProcessTransfer())->setName($processName)),
            );
        }

        return $stateCollectionTransfer;
    }

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderWithProductPackagingUnits(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->addItem($this->createItemWithProductPackagingUnit())
            ->addItem($this->createItemWithProductPackagingUnit());

        $quoteTransfer
            ->setCustomer($this->haveCustomer())
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']));

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithProductPackagingUnit(): ItemTransfer
    {
        $productConcreteTransfer = $this->haveProduct();
        $boxProductConcreteTransfer = $this->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => 'box',
        ]);

        $this->haveProductPackagingUnit(
            [
                SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
                SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
                SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ],
            [
                SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => 1,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_MIN => 1,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_MAX => null,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_INTERVAL => 1,
                SpyProductPackagingUnitEntityTransfer::IS_AMOUNT_VARIABLE => true,
            ],
        );

        $productMeasurementUnitTransfer = (new ProductMeasurementUnitTransfer())->fromArray(
            $this->haveProductMeasurementUnit([
                SpyProductMeasurementUnitEntityTransfer::CODE => 'MYCODE' . random_int(1, 100),
            ])->toArray(),
            true,
        );

        $productMeasurementBaseUnitTransfer = (new ProductMeasurementBaseUnitTransfer())->fromArray(
            $this->haveProductMeasurementBaseUnit(
                $productConcreteTransfer->getFkProductAbstract(),
                $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            )->toArray(),
            true,
        )
            ->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray(
            $this->haveProductMeasurementSalesUnit(
                $boxProductConcreteTransfer->getIdProductConcrete(),
                $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
                $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
            )->toArray(),
            true,
        );

        $productMeasurementSalesUnitTransfer
            ->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer)
            ->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return (new ItemBuilder())->build()
            ->setQuantity(1)
            ->setSku($productConcreteTransfer->getSku())
            ->setAmount(2)
            ->setAmountSalesUnit($productMeasurementSalesUnitTransfer);
    }

    /**
     * @param bool $selfLead
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function havePackagingUnitAndLead(bool $selfLead): array
    {
        $leadProductConcreteTransfer = $this->haveProduct();
        if ($selfLead) {
            return [
                $leadProductConcreteTransfer,
                $leadProductConcreteTransfer,
            ];
        }

        $packagingUnitProductConcreteTransfer = $this->haveProduct();

        return [
            $leadProductConcreteTransfer,
            $packagingUnitProductConcreteTransfer,
        ];
    }
}
