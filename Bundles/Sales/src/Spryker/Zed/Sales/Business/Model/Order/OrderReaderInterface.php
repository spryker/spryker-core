<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

interface OrderReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctOrderStates($idSalesOrder);
}
