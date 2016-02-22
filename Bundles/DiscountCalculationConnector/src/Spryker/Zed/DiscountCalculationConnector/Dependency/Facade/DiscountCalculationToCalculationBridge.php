<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class DiscountCalculationToCalculationBridge implements DiscountCalculationToCalculationInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacade $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateGrandTotalTotals(TotalsTransfer $totalsTransfer, CalculableInterface $calculableContainer, $calculableItems)
    {
        $this->calculationFacade->recalculateGrandTotalTotals($totalsTransfer, $calculableContainer, $calculableItems);
    }

}
