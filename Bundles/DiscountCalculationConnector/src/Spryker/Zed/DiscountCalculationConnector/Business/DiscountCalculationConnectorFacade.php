<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorBusinessFactory getFactory()
 */
class DiscountCalculationConnectorFacade extends AbstractFacade implements DiscountCalculationConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     *
     * @return void
     */
    public function recalculateDiscountTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $discountableContainer,
        \ArrayObject $discountableContainers
    ) {
        $calculator = $this->getFactory()->createDiscountTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $discountableContainer, $discountableContainers);
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \ArrayObject $items
     *
     * @return void
     */
    public function recalculateGrandTotalWithDiscountsTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $container,
        \ArrayObject $items
    ) {
        $calculator = $this->getFactory()->createGrandTotalWithDiscountsTotalsCalculator();
        $calculator->recalculateTotals($totalsTransfer, $container, $items);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return void
     */
    public function recalculateRemoveAllCalculatedDiscounts(CalculableInterface $container)
    {
        $calculator = $this->getFactory()->createRemoveAllCalculatedDiscountsCalculator();
        $calculator->recalculate($container);
    }

}
