<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;

interface TaxAppFacadeInterface
{
    /**
     * Specification:
     * - Saves tax app config.
     * - Requires TaxAppConfigTransfer.TaxAppConfigConditionsTransfer.applicationId.
     * - Requires TaxAppConfigTransfer.TaxAppConfigConditionsTransfer.apiUrls.
     * - Requires TaxAppConfigTransfer.TaxAppConfigConditionsTransfer.vendorCode.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return void
     */
    public function saveTaxAppConfig(TaxAppConfigTransfer $taxAppConfigTransfer): void;

    /**
     * Specification:
     * - Deletes tax app config.
     * - Requires TaxAppConfigCriteriaTransfer.TaxAppConfigConditionsTransfer.vendorCode.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return void
     */
    public function deleteTaxAppConfig(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): void;

    /**
     * Specification:
     * - Requires `CalculableObject.store.name` to be set.
     * - Executes {@link \Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface} plugins stack.
     * - Does nothing if `CalculableObjectTransfer.expenses` does not have items of `ShipmentConfig::SHIPMENT_EXPENSE_TYPE` type.
     * - Dispatch tax quotation request to ACP Apps.
     * - Sets `CalculableObject.totals.taxTotal` with returned amount, if tax quotation request is successful.
     * - Sets `CalculableObject.totals.taxTotal` to 0 and overwrites other calculated taxes until a shipment is selected.
     * - Sets 'Item.UnitPriceToPayAggregation', 'Item.SumPriceToPayAggregation', 'Item.UnitTaxAmountFullAggregation' and 'Item.SumTaxAmountFullAggregation' with returned amounts, if tax quotation request is successful.
     * - Sets 'Expense.UnitTaxAmount', 'Expense.SumTaxAmount', 'Expense.UnitPriceToPayAggregation' and 'Expense.SumPriceToPayAggregation' (if expense type is shipment) with returned amounts, if tax quotation request is successful.
     * - Recalculation does not trigger additional API calls when TaxAppSale data was not changed.
     * - Executes fallback {@link \Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface} plugins stack if tax app config is missing or inactive.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder`, `OrderTransfer.store.name` to be set.
     * - Executes {@link \Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface} plugins stack.
     * - Sends `SubmitPaymentTaxInvoice` message to the message broker.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function sendSubmitPaymentTaxInvoiceMessage(OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Executes {@link \Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface} plugins stack.
     * - Sends a refund request to Tax App.
     *
     * @api
     *
     * @param array<int> $orderItemIds
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function processOrderRefund(array $orderItemIds, int $idSalesOrder): void;
}
