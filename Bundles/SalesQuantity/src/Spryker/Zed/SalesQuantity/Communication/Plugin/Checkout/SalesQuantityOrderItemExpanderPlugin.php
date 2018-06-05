<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Communication\Plugin\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface getFacade()
 */
class SalesQuantityOrderItemExpanderPlugin extends AbstractPlugin implements SalesOrderItemExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return null|\ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItem(ItemTransfer $itemTransfer): ?ArrayObject
    {
        return $this->getFacade()->expandOrderItem($itemTransfer);
    }
}
