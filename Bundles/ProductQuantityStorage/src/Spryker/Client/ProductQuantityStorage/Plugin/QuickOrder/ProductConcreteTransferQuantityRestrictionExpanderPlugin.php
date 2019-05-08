<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class ProductConcreteTransferQuantityRestrictionExpanderPlugin extends AbstractPlugin implements ProductConcreteExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    public function expand(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductQuantityExpander()
            ->expandProductConcreteTransfersWithQuantityRestrictions($productConcreteTransfers);
    }
}
