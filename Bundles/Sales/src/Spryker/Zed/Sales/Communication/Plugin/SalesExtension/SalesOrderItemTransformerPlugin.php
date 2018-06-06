<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Plugin\SalesExtension;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemTransformerPluginInterface;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class SalesOrderItemTransformerPlugin extends AbstractPlugin implements SalesOrderItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformOrderItem(ItemTransfer $itemTransfer): ArrayObject
    {
        return $this->getFacade()->transformOrderItem($itemTransfer);
    }
}
