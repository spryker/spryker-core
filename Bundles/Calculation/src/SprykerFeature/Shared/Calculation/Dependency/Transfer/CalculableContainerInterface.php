<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface CalculableContainerInterface extends ExpenseContainerInterface
{

    /**
     * @return \ArrayObject|CalculableItemInterface[]
     */
    public function getItems();

    /**
     * @return TotalsInterface
     */
    public function getTotals();

    /**
     * @param \ArrayObject $items
     *
     * @return $this
     */
    public function setItems(\ArrayObject $items);

    /**
     * @param TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals(TotalsInterface $totals);
}
