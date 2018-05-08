<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ManualOrderEntry\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\OrderSourceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ManualOrderEntryHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function createEmptySpySalesOrderEntityTransfer()
    {
        return new SpySalesOrderEntityTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithOrderSource()
    {
        $quoteTransfer = new QuoteTransfer();
        $orderSourceTransfer = (new OrderSourceTransfer())
            ->setIdOrderSource(1);
        $quoteTransfer->setOrderSource($orderSourceTransfer);

        return $quoteTransfer;
    }
}
