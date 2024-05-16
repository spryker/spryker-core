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
use Generated\Shared\Transfer\SaleTaxMetadataTransfer;
use Generated\Shared\Transfer\ShippingWarehouseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppAddressTransfer;
use Generated\Shared\Transfer\TaxAppItemTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxAppShipmentTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetrieverInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use Spryker\Zed\TaxApp\TaxAppConfig;

class TaxAppMapper implements TaxAppMapperInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const ORIGINAL_TRANSFER_MISSING_EXCEPTION = 'Could not get original transfer from CalculableObjectTransfer';

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface
     */
    protected AddressMapperInterface $addressMapper;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetrieverInterface
     */
    protected ItemExpensePriceRetrieverInterface $priceFormatter;

    /**
     * @var \Spryker\Zed\TaxApp\TaxAppConfig
     */
    protected TaxAppConfig $taxAppConfig;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface $addressMapper
     * @param \Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetrieverInterface $priceFormatter
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\TaxAppConfig $taxAppConfig
     */
    public function __construct(
        AddressMapperInterface $addressMapper,
        ItemExpensePriceRetrieverInterface $priceFormatter,
        TaxAppToStoreFacadeInterface $storeFacade,
        TaxAppConfig $taxAppConfig
    ) {
        $this->addressMapper = $addressMapper;
        $this->priceFormatter = $priceFormatter;
        $this->storeFacade = $storeFacade;
        $this->taxAppConfig = $taxAppConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    public function mapCalculableObjectToTaxAppSaleTransfer(
        CalculableObjectTransfer $calculableObjectTransfer,
        TaxAppSaleTransfer $taxAppSaleTransfer
    ): TaxAppSaleTransfer {
        $taxAppSaleTransfer = $taxAppSaleTransfer->fromArray($calculableObjectTransfer->toArray(), true);
        $saleItemTransfers = new ArrayObject();
        $saleShipmentTransfers = new ArrayObject();

        if (!$calculableObjectTransfer->getTaxMetadata()) {
            $taxAppSaleTransfer->setTaxMetadata([]);
        }

        $originalTransfer = $this->getOriginalTransfer($calculableObjectTransfer);
        $transferIdentifier = $this->getTransferIdentifier($originalTransfer);

        $documentDate = (new DateTime())->format('Y-m-d');
        if (method_exists($originalTransfer, 'getCreatedAt') && $originalTransfer->getCreatedAt()) {
            $createdAt = DateTime::createFromFormat('Y-m-d H:i:s.u', $originalTransfer->getCreatedAt());
            $documentDate = $createdAt ? $createdAt->format('Y-m-d') : $documentDate;
        }

        $taxAppSaleTransfer
            ->setTransactionId($transferIdentifier)
            ->setDocumentNumber($transferIdentifier)
            ->setDocumentDate($documentDate)
            ->setPriceMode($calculableObjectTransfer->getPriceModeOrFail());

        foreach ($calculableObjectTransfer->getItems() as $itemIndex => $itemTransfer) {
            $taxAppItemTransfer = $this->mapItemTransfersToSaleItemTransfers(
                $itemTransfer,
                $calculableObjectTransfer->getPriceModeOrFail(),
                $originalTransfer->getBillingAddress(),
                $itemIndex,
            );

            $saleItemTransfers->append($taxAppItemTransfer);
        }

        foreach ($calculableObjectTransfer->getExpenses() as $hash => $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $taxAppShipmentTransfer = $this->mapExpenseTransferToSaleShipmentTransfer(
                $expenseTransfer,
                $calculableObjectTransfer->getPriceModeOrFail(),
                $originalTransfer->getBillingAddress(),
            );

            $taxAppShipmentTransfer->setId($hash);
            $saleShipmentTransfers->append($taxAppShipmentTransfer);
        }

        $taxAppSaleTransfer->setItems($saleItemTransfers);
        $taxAppSaleTransfer->setShipments($saleShipmentTransfers);

        $taxAppSaleTransfer = $this->setTaxSaleCountryCode($calculableObjectTransfer, $taxAppSaleTransfer, $originalTransfer);

        return $taxAppSaleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\AddressTransfer|null $billingAddressTransfer
     * @param int $itemIndex
     *
     * @return \Generated\Shared\Transfer\TaxAppItemTransfer
     */
    public function mapItemTransfersToSaleItemTransfers(
        ItemTransfer $itemTransfer,
        string $priceMode,
        ?AddressTransfer $billingAddressTransfer,
        int $itemIndex
    ): TaxAppItemTransfer {
        $taxAppItemTransfer = new TaxAppItemTransfer();

        $taxAppItemTransfer->setId(sprintf('%s_%s', $itemTransfer->getSku(), $itemIndex));
        $taxAppItemTransfer->setSku($itemTransfer->getSku());
        $taxAppItemTransfer->setQuantity($itemTransfer->getQuantity());

        $taxAppItemTransfer->setPriceAmount($this->priceFormatter->getUnitPriceWithoutDiscount($itemTransfer, $priceMode));

        if ($itemTransfer->getCanceledAmount()) {
            $taxAppItemTransfer->setRefundableAmount($this->priceFormatter->getUnitPriceWithoutDiscount($itemTransfer, $priceMode));
        }

        $taxAppItemTransfer->setDiscountAmount($itemTransfer->getUnitDiscountAmountFullAggregation());

        if ($itemTransfer->getShipment() && $itemTransfer->getShipment()->getShippingAddress()) {
            $shippingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($itemTransfer->getShipment()->getShippingAddress(), new TaxAppAddressTransfer());
            $taxAppItemTransfer->setShippingAddress($shippingTaxAppAddressTransfer);
        }

        if ($billingAddressTransfer && $billingAddressTransfer->getCountry()) {
            $billingTaxAppAddressTransfer = $this->addressMapper->mapAddressTransferToTaxAppAddressTransfer($billingAddressTransfer, new TaxAppAddressTransfer());
            $taxAppItemTransfer->setBillingAddress($billingTaxAppAddressTransfer);
        }

        if ($itemTransfer->getMerchantProfileAddress()) {
            $sellerAddress = $this->addressMapper->mapMerchantProfileAddressTransferToTaxAppAddressTransfer($itemTransfer->getMerchantProfileAddress(), new TaxAppAddressTransfer());
            $taxAppItemTransfer->setSellerAddress($sellerAddress);
        }

        if (!$itemTransfer->getTaxMetadata()) {
            $taxAppItemTransfer->setTaxMetadata([]);
        }

        if ($itemTransfer->getMerchantStockAddresses()->count()) {
            foreach ($itemTransfer->getMerchantStockAddresses() as $merchantStockAddress) {
                $shippingWarehouseTransfer = $this->mapMerchantStockAddressTransferToShippingWarehouse(
                    $taxAppItemTransfer,
                    $merchantStockAddress,
                    new ShippingWarehouseTransfer(),
                );

                $taxAppItemTransfer->addShippingWarehouse($shippingWarehouseTransfer);
            }
        }

        return $taxAppItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppItemTransfer $taxAppItemTransfer
     * @param \Generated\Shared\Transfer\MerchantStockAddressTransfer $merchantStockAddressTransfer
     * @param \Generated\Shared\Transfer\ShippingWarehouseTransfer $shippingWarehouseTransfer
     *
     * @return \Generated\Shared\Transfer\ShippingWarehouseTransfer
     */
    public function mapMerchantStockAddressTransferToShippingWarehouse(
        TaxAppItemTransfer $taxAppItemTransfer,
        MerchantStockAddressTransfer $merchantStockAddressTransfer,
        ShippingWarehouseTransfer $shippingWarehouseTransfer
    ): ShippingWarehouseTransfer {
        $quantityToShip = 0;
        if ($merchantStockAddressTransfer->getQuantityToShip()) {
            $quantityToShip = $merchantStockAddressTransfer->getQuantityToShip()->toInt();
        }

        $shippingWarehouseTransfer->setQuantity($quantityToShip);

        if ($merchantStockAddressTransfer->getStockAddress()) {
            $warehouseAddress = $this->addressMapper->mapStockAddressTransferToTaxAppAddressTransfer($merchantStockAddressTransfer->getStockAddress(), new TaxAppAddressTransfer());
            $shippingWarehouseTransfer->setWarehouseAddress($warehouseAddress);
        }

        return $shippingWarehouseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\AddressTransfer|null $billingAddressTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppShipmentTransfer
     */
    public function mapExpenseTransferToSaleShipmentTransfer(
        ExpenseTransfer $expenseTransfer,
        string $priceMode,
        ?AddressTransfer $billingAddressTransfer
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

        $taxAppShipmentTransfer->setPriceAmount($this->priceFormatter->getSumPriceWithoutDiscount($expenseTransfer, $priceMode));

        if ($expenseTransfer->getCanceledAmount()) {
            $taxAppShipmentTransfer->setRefundableAmount($this->priceFormatter->getSumPriceWithoutDiscount($expenseTransfer, $priceMode));
        }
        $taxAppShipmentTransfer->setDiscountAmount($expenseTransfer->getSumDiscountAmountAggregation());

        return $taxAppShipmentTransfer;
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
            $transferIdentifier = $transfer->getUuid() ?? Uuid::uuid4()->toString();
            //@phpstan-ignore-next-line
            $transfer->setUuid($transferIdentifier);
        }

        if (method_exists($transfer, 'getOrderReference') && !$transferIdentifier) {
            $transferIdentifier = $transfer->getOrderReference();
        }

        if (!$transferIdentifier) {
            return Uuid::uuid4()->toString();
        }

        return $transferIdentifier;
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
        if (!$orderTransfer->getTaxMetadata()) {
            $calculableObjectTransfer->setTaxMetadata(new SaleTaxMetadataTransfer());
        }

        $calculableObjectTransfer->setStore((new StoreTransfer())->setName($orderTransfer->getStore()));
        $calculableObjectTransfer->setOriginalOrder($orderTransfer);

        return $this->mapCalculableObjectToTaxAppSaleTransfer($calculableObjectTransfer, $taxAppSaleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $originalTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    public function setTaxSaleCountryCode(
        CalculableObjectTransfer $calculableObjectTransfer,
        TaxAppSaleTransfer $taxAppSaleTransfer,
        OrderTransfer|QuoteTransfer $originalTransfer
    ): TaxAppSaleTransfer {
        $sellerCountryCode = $customerCountryCode = $this->findStoreCountryCode($calculableObjectTransfer);

        if ($this->taxAppConfig->getSellerCountryCode()) {
            $sellerCountryCode = $this->taxAppConfig->getSellerCountryCode();
        }

        if ($this->taxAppConfig->getCustomerCountryCode()) {
            $customerCountryCode = $this->taxAppConfig->getCustomerCountryCode();
        }

        if ($originalTransfer->getBillingAddress() && $originalTransfer->getBillingAddress()->getIso2Code()) {
            $customerCountryCode = $originalTransfer->getBillingAddress()->getIso2Code();
        }

        $taxAppSaleTransfer->setSellerCountryCode($sellerCountryCode ?: null);
        $taxAppSaleTransfer->setCustomerCountryCode($customerCountryCode ?: null);

        return $taxAppSaleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return string|null
     */
    protected function findStoreCountryCode(CalculableObjectTransfer $calculableObjectTransfer): ?string
    {
        if (!empty($calculableObjectTransfer->getStoreOrFail()->getCountries()[0])) {
            return $calculableObjectTransfer->getStoreOrFail()->getCountries()[0];
        }

        $storeTransfer = $this->storeFacade->getStoreByName($calculableObjectTransfer->getStoreOrFail()->getNameOrFail());

        return $storeTransfer->getCountries() !== [] ? $storeTransfer->getCountries()[0] : null;
    }
}
