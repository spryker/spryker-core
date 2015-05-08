<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

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
     * @param \ArrayObject $totals
     *
     * @return mixed
     */
    public function setTotals(\ArrayObject $totals);
}
