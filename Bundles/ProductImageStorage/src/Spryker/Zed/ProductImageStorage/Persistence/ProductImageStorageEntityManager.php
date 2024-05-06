<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStoragePersistenceFactory getFactory()
 */
class ProductImageStorageEntityManager extends AbstractEntityManager implements ProductImageStorageEntityManagerInterface
{
    /**
     * @param list<int> $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractImageStorageByProductAbstractIds(array $productAbstractIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productAbstractImageStorageCollection */
        $productAbstractImageStorageCollection = $this->getFactory()
            ->createSpyProductAbstractImageStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        $productAbstractImageStorageCollection->delete();
    }
}
