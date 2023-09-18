<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Mapper;

use ArrayObject;
use DateTime;
use Exception;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantStockAddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppAddressTransfer;
use Generated\Shared\Transfer\TaxAppItemTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxAppShipmentTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\PriceFormatterInterface;

class TaxAppMapper implements TaxAppMapperInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const ORIGINAL_TRANSFER_MISSING_EXCEPTION = 'Could not get original transfer from CalculableObjectTransfer';

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface
     */
    protected AddressMapperInterface $addressMapper;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\Prices\PriceFormatterInterface
     */
    protected PriceFormatterInterface $priceFormatter;

    /**
     * @param \Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface $addressMapper
     * @param \Spryker\Zed\TaxApp\Business\Mapper\Prices\PriceFormatterInterface $priceFormatter
     */
    public function __construct(AddressMapperInterface $addressMapper, PriceFormatterInterface $priceFormatter)
    {
        $this->addressMapper = $addressMapper;
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * @inheritDoc
     */
    public function mapCalculableObjectToTaxAppSaleTransfer(
        CalculableObjectTransfer $calculableObjectTransfer,
        TaxAppSaleTransfer $taxAppSaleTransfer
    ): TaxAppSaleTransfer {
        $taxAppSaleTransfer = $taxAppSaleTransfer->fromArray($calculableObjectTransfer->toArray(), true);
        $saleItemTransfers = new ArrayObject();
        $saleShipmentTransfers = new ArrayObject();

        if (!$taxAppSaleTransfer->getTaxMetadata()) {
            $taxAppSaleTransfer->setTaxMetadata([]);
        }

        $originalTransfer = $this->getOriginalTransfer($calculableObjectTransfer);
        $transferIdentifier = $this->getTransferIdentifier($originalTransfer);

        $documentDate = (new DateTime())->format('Y-m-d');

        $taxAppSaleTransfer
            ->setTransactionId($transferIdentifier)
            ->setDocumentNumber($transferIdentifier)
            ->setDocumentDate($documentDate);

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $taxAppItemTransfer = $this->mapItemTransfersToSaleItemTransfers(
                $itemTransfer,
                $originalTransfer->getBillingAddress(),
                $calculableObjectTransfer->getPriceMode(),
            );

            if ($itemTransfer->getMerchantStockAddresses()->count()) {
                $saleItemTransfers = $this->duplicateTaxAppItemTransferPerMerchantStockAddress($saleItemTransfers, $itemTransfer, $taxAppItemTransfer);

                continue;
            }

            $saleItemTransfers->append($taxAppItemTransfer);
        }

        foreach ($calculableObjectTransfer->getExpenses() as $hash => $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $taxAppShipmentTransfer = $this->mapExpenseTransferToSaleShipmentTransfer($expenseTransfer, $originalTransfer->getBillingAddress(), $calculableObjectTransfer->getPriceMode());
            $taxAppShipmentTransfer->setId($hash);
            $saleShipmentTransfers->append($taxAppShipmentTransfer);
        }

        $taxAppSaleTransfer->setItems($saleItemTransfers);
        $taxAppSaleTransfer->setShipments($saleShipmentTransfers);

        return $taxAppSaleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer|null $billingAddressTransfer
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\TaxAppItemTransfer
     */
    public function mapItemTransfersToSaleItemTransfers(
        ItemTransfer $itemTransfer,
        ?AddressTransfer $billingAddressTransfer,
        ?string $priceMode
    ): TaxAppItemTransfer {
        $taxAppItemTransfer = new TaxAppItemTransfer();

        $taxAppItemTransfer->fromArray($itemTransfer->toArray(true, true), true);
        $taxAppItemTransfer->setId($itemTransfer->getSku());

        $taxAppItemTransfer->setPriceAmount($this->priceFormatter->getSumPrice($itemTransfer, $priceMode));

        $taxAppItemTransfer->setDiscountAmount($this->priceFormatter->priceToString($itemTransfer->getSumDiscountAmountFullAggregation()));

        if ($itemTransfer->getShipment() && $itemTransfer->getShipment()->getShippingAddress()) {
            $shippingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($itemTransfer->getShipment()->getShippingAddress(), new TaxAppAddressTransfer());
            $taxAppItemTransfer->setShippingAddress($shippingTaxAppAddressTransfer);
        }

        if ($billingAddressTransfer) {
            $billingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($billingAddressTransfer, new TaxAppAddressTransfer());
            $taxAppItemTransfer->setBillingAddress($billingTaxAppAddressTransfer);
        }

        if ($itemTransfer->getMerchantProfileAddress()) {
            $sellerAddress = $this->addressMapper->mapMerchantProfileAddressTransferToTaxAppAddressTransfer($itemTransfer->getMerchantProfileAddress(), new TaxAppAddressTransfer());
            $taxAppItemTransfer->setSellerAddress($sellerAddress);
        }

        if (!$taxAppItemTransfer->getTaxMetadata()) {
            $taxAppItemTransfer->setTaxMetadata([]);
        }

        return $taxAppItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppItemTransfer
     * @param \Generated\Shared\Transfer\MerchantStockAddressTransfer $merchantStockAddressTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppItemTransfer
     */
    public function mapMerchantStockAddressTransferToSaleItemTransfer(
        TaxAppItemTransfer $taxAppItemTransfer,
        MerchantStockAddressTransfer $merchantStockAddressTransfer
    ): TaxAppItemTransfer {
        $quantityToShip = '0';
        if ($merchantStockAddressTransfer->getQuantityToShip()) {
            $quantityToShip = $merchantStockAddressTransfer->getQuantityToShip()->toString();
        }

        $taxAppItemTransfer->setQuantity($quantityToShip);

        if ($merchantStockAddressTransfer->getStockAddress()) {
            $warehouseAddress = $this->addressMapper->mapStockAddressTransferToTaxAppAddressTransfer($merchantStockAddressTransfer->getStockAddress(), new TaxAppAddressTransfer());
            $taxAppItemTransfer->setWarehouseAddress($warehouseAddress);
        }

        return $taxAppItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer|null $billingAddressTransfer
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\TaxAppShipmentTransfer
     */
    public function mapExpenseTransferToSaleShipmentTransfer(
        ExpenseTransfer $expenseTransfer,
        ?AddressTransfer $billingAddressTransfer,
        ?string $priceMode
    ): TaxAppShipmentTransfer {
        $taxAppShipmentTransfer = new TaxAppShipmentTransfer();

        if ($expenseTransfer->getShipment() && $expenseTransfer->getShipment()->getShippingAddress()) {
            $shippingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($expenseTransfer->getShipment()->getShippingAddress(), new TaxAppAddressTransfer());

            $taxAppShipmentTransfer->setShippingAddress($shippingTaxAppAddressTransfer);
        }

        if ($billingAddressTransfer) {
            $billingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($billingAddressTransfer, new TaxAppAddressTransfer());
            $taxAppShipmentTransfer->setBillingAddress($billingTaxAppAddressTransfer);
        }

        $taxAppShipmentTransfer->setPriceAmount($this->priceFormatter->getSumPrice($expenseTransfer, $priceMode));
        $taxAppShipmentTransfer->setDiscountAmount($this->priceFormatter->priceToString($expenseTransfer->getSumDiscountAmountAggregation()));

        return $taxAppShipmentTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\TaxAppItemTransfer> $saleItemTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppItemTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\TaxAppItemTransfer>
     */
    protected function duplicateTaxAppItemTransferPerMerchantStockAddress(
        ArrayObject $saleItemTransfers,
        ItemTransfer $itemTransfer,
        TaxAppItemTransfer $taxAppItemTransfer
    ): ArrayObject {
        foreach ($itemTransfer->getMerchantStockAddresses() as $merchantStockAddress) {
            $taxAppItemTransferWithWarehouseAddress = $this->mapMerchantStockAddressTransferToSaleItemTransfer(
                clone $taxAppItemTransfer,
                $merchantStockAddress,
            );

            $saleItemTransfers->append($taxAppItemTransferWithWarehouseAddress);
        }

        return $saleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getOriginalTransfer(CalculableObjectTransfer $calculableObjectTransfer): OrderTransfer|QuoteTransfer
    {
        if ($calculableObjectTransfer->getOriginalQuote() !== null) {
            return $calculableObjectTransfer->getOriginalQuote();
        }

        if ($calculableObjectTransfer->getOriginalOrder() !== null) {
            return $calculableObjectTransfer->getOriginalOrder();
        }

        throw new Exception(static::ORIGINAL_TRANSFER_MISSING_EXCEPTION);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return string
     */
    protected function getTransferIdentifier(OrderTransfer|QuoteTransfer $transfer): string
    {
        $transferIdentifier = null;

        if (method_exists($transfer, 'getUuid')) {
            $transferIdentifier = $transfer->getUuid();
        }

        if (method_exists($transfer, 'getOrderReference') && !$transferIdentifier) {
            $transferIdentifier = $transfer->getOrderReference();
        }

        return $transferIdentifier ?? Uuid::uuid4()->toString();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    public function mapOrderTransferToTaxAppSaleTransfer(OrderTransfer $orderTransfer, TaxAppSaleTransfer $taxAppSaleTransfer): TaxAppSaleTransfer
    {
        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->fromArray($orderTransfer->toArray(), true);
        $calculableObjectTransfer->setStore((new StoreTransfer())->setName($orderTransfer->getStore()));
        $calculableObjectTransfer->setOriginalOrder($orderTransfer);

        return $this->mapCalculableObjectToTaxAppSaleTransfer($calculableObjectTransfer, $taxAppSaleTransfer);
    }
}
