<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Communication\Plugin\SalesReturn;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPolicyPluginInterface;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class OutdatedOrderItemReturnPolicyPlugin extends AbstractPlugin implements ReturnPolicyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes outdated order items.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function execute(ArrayObject $itemTransfers): ArrayObject
    {
        return $this->getFacade()->sanitizeOutdatedOrderItems($itemTransfers);
    }
}
