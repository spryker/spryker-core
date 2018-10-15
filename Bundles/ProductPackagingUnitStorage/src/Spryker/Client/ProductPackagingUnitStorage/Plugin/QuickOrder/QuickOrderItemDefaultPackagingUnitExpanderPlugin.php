<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface getClient()
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageFactory getFactory()
 */
class QuickOrderItemDefaultPackagingUnitExpanderPlugin extends AbstractPlugin implements QuickOrderItemExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ItemTransfer with packaging unit data if available.
     * - Uses the default amount and default measurement unit settings.
     * - Returns ItemTransfer unchanged if no packaging unit data is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getClient()->expandItemTransferWithDefaultPackagingUnit($itemTransfer);
    }
}
