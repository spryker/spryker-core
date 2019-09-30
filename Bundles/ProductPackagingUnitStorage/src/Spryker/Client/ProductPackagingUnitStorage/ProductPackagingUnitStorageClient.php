<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageFactory getFactory()
 */
class ProductPackagingUnitStorageClient extends AbstractClient implements ProductPackagingUnitStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductConcrete): ?ProductConcretePackagingStorageTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitStorageReader()
            ->findProductConcretePackagingById($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->getFactory()
            ->createItemTransferExpander()
            ->expandWithDefaultPackagingUnit($itemTransfer);
    }
}
