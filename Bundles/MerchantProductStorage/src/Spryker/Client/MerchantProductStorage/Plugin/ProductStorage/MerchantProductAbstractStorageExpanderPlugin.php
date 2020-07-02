<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\MerchantProductStorage\MerchantProductStorageFactory getFactory()
 */
class MerchantProductAbstractStorageExpanderPlugin extends AbstractPlugin implements ProductAbstractStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided ProductAbstractStorage transfer object.
     * - Finds merchant product relation for ProductAbstractStorage.idProductAbstract.
     * - Sets ProductAbstractStorage.merchantReference from found merchant product relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expand(ProductAbstractStorageTransfer $productAbstractStorageTransfer): ProductAbstractStorageTransfer
    {
        $merchantProductStorageTransfer = $this->getFactory()
            ->createMerchantProductStorageReader()
            ->findOne($productAbstractStorageTransfer->getIdProductAbstract());

        if ($merchantProductStorageTransfer) {
            $productAbstractStorageTransfer->setMerchantReference($merchantProductStorageTransfer->getMerchantReference());
        }

        return $productAbstractStorageTransfer;
    }
}
