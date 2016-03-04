<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface CalculationFacadeInterface
{

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|null $calculableItems
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function recalculateTotals(CalculableInterface $calculableContainer, $calculableItems = null);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateExpensePriceToPay(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateExpenseTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateGrandTotalTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateItemPriceToPay(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateOptionPriceToPay(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveAllExpenses(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function recalculateRemoveTotals(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return void
     */
    public function calculateItemTotalPrice(CalculableInterface $calculableContainer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateSubtotalWithoutItemExpensesTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateTaxTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems);

}
