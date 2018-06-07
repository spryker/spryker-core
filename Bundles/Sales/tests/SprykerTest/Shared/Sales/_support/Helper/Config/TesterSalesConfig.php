<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper\Config;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\SalesConfig;

class TesterSalesConfig extends SalesConfig
{
    /**
     * @var string
     */
    protected $stateMachineProcessName;

    /**
     * @param string $stateMachineProcessName
     *
     * @return void
     */
    public function setStateMachineProcessName($stateMachineProcessName)
    {
        $this->stateMachineProcessName = $stateMachineProcessName;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function determineProcessForOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        return $this->stateMachineProcessName;
    }
}
