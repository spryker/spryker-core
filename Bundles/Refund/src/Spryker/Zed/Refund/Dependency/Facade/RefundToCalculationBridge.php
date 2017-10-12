<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

class RefundToCalculationBridge implements RefundToCalculationInterface
{
    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacadeInterface $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculateOrder(OrderTransfer $orderTransfer)
    {
        return $this->calculationFacade->recalculateOrder($orderTransfer);
    }
}
