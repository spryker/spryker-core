<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantStockAddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShippingWarehouseTransfer;
use Generated\Shared\Transfer\TaxAppItemTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxAppShipmentTransfer;

interface TaxAppMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    public function mapCalculableObjectToTaxAppSaleTransfer(
        CalculableObjectTransfer $calculableObjectTransfer,
        TaxAppSaleTransfer $taxAppSaleTransfer
    ): TaxAppSaleTransfer;

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
    ): TaxAppItemTransfer;

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
    ): ShippingWarehouseTransfer;

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
    ): TaxAppShipmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    public function mapOrderTransferToTaxAppSaleTransfer(OrderTransfer $orderTransfer, TaxAppSaleTransfer $taxAppSaleTransfer): TaxAppSaleTransfer;
}
