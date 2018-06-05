<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesQuantity\Business\Model\Order\OrderItemExpander;

/**
 * @method \Spryker\Zed\SalesQuantity\SalesQuantityConfig getConfig()
 */
class SalesQuantityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesQuantity\Business\Model\Order\OrderItemExpanderInterface
     */
    public function createOrderItemExpander()
    {
        return new OrderItemExpander();
    }
}
