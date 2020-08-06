<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOms\Business\OrderItem\OrderItemExpander;
use Spryker\Zed\SalesOms\Business\OrderItem\OrderItemExpanderInterface;

/**
 * @method \Spryker\Zed\SalesOms\SalesOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesOms\Persistence\SalesOmsRepositoryInterface getRepository()
 */
class SalesOmsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesOms\Business\OrderItem\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }
}
