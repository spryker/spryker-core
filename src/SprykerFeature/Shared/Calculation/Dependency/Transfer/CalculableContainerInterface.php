<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface CalculableContainerInterface extends ExpenseContainerInterface
{

    /**
     * @return CalculableItemCollectionInterface|CalculableItemInterface[]
     */
    public function getItems();

    /**
     * @return TotalsInterface
     */
    public function getTotals();

    /**
     * @param CalculableItemCollectionInterface $items
     *
     * @return $this
     */
    public function setItems(CalculableItemCollectionInterface $items);

    /**
     * @param TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals(TotalsInterface $totals);
}
