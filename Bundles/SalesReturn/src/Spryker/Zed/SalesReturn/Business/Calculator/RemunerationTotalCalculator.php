<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;

class RemunerationTotalCalculator implements RemunerationTotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateRemunerationTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $calculableObjectTransfer->requireTotals();

        $remunerationTotal = $this->calculateItemRemunerationTotal($calculableObjectTransfer);

        $calculableObjectTransfer->getTotals()
            ->setRemunerationTotal($remunerationTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateItemRemunerationTotal(CalculableObjectTransfer $calculableObjectTransfer): int
    {
        $remunerationTotal = 0;

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $remunerationTotal += $itemTransfer->getRemunerationAmount();
        }

        return $remunerationTotal;
    }
}
