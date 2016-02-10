<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountCalculationConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param \ArrayObject $discountableContainers
     *
     * @return void
     */
    public function recalculateDiscountTotals(TotalsTransfer $totalsTransfer, CalculableInterface $discountableContainer, \ArrayObject $discountableContainers);

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \ArrayObject $items
     *
     * @return void
     */
    public function recalculateGrandTotalWithDiscountsTotals(TotalsTransfer $totalsTransfer, CalculableInterface $container, \ArrayObject $items);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return void
     */
    public function recalculateRemoveAllCalculatedDiscounts(CalculableInterface $container);

}
