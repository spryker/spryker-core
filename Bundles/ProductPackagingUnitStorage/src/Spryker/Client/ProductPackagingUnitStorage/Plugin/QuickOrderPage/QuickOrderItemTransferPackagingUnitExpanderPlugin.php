<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderItemTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface getClient()
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageFactory getFactory()
 */
class QuickOrderItemTransferPackagingUnitExpanderPlugin extends AbstractPlugin implements QuickOrderItemTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getClient()->expandItemTransferWithPackagingUnit($itemTransfer);
    }
}
