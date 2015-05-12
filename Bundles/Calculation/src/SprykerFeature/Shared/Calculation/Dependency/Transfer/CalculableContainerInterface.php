<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\CalculationTotalsTransfer;

interface CalculableContainerInterface extends ExpenseContainerInterface
{

    /**
     * @return \ArrayObject|CalculableItemInterface[]
     */
    public function getItems();

    /**
     * @return \ArrayObject|TotalsInterface[]
     */
    public function getTotals();

    /**
     * @param \ArrayObject $items
     *
     * @return $this
     */
    public function setItems(\ArrayObject $items);

    /**
     * @param CalculationTotalsTransfer $totals
     *
     * @return mixed
     */
    public function setTotals(CalculationTotalsTransfer $totals);
}
