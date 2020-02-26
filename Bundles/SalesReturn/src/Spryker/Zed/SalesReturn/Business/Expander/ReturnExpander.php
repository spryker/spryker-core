<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface;

class ReturnExpander implements ReturnExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface
     */
    protected $returnTotalCalculator;

    /**
     * @param \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface $returnTotalCalculator
     */
    public function __construct(ReturnTotalCalculatorInterface $returnTotalCalculator)
    {
        $this->returnTotalCalculator = $returnTotalCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expandReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnTransfer->setReturnTotals(
            $this->returnTotalCalculator->calculateReturnTotals($returnTransfer)
        );

        return $returnTransfer;
    }
}
