<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface getClient()
 */
class ProductConcreteTransferQuantityRestrictionsExpanderPlugin extends AbstractPlugin implements ProductConcreteExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ProductConcreteTransfer with quantity restrictions for given product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expand(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getClient()->expandProductConcreteTransferWithQuantityRestrictions($productConcreteTransfer);
    }
}
