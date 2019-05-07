<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageFactory getFactory()
 */
class ProductConcreteTransferQuantityRestrictionsExpanderPlugin extends AbstractPlugin implements ProductConcreteExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expand(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductQuantityExpander()
            ->expandProductConcreteTransferWithQuantityRestrictions($productConcreteTransfer);
    }
}
