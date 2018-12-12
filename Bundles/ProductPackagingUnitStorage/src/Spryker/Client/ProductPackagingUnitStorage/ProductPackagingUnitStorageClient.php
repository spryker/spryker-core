<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null
     */
    public function findProductAbstractPackagingById(int $idProductAbstract): ?ProductAbstractPackagingStorageTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitStorageReader()
            ->findProductAbstractPackagingById($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductAbstract, int $idProduct): ?ProductConcretePackagingStorageTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitStorageReader()
            ->findProductConcretePackagingById($idProductAbstract, $idProduct);
    }
}
