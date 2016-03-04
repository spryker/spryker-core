<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;

interface OrderManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransger
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransger);

}
