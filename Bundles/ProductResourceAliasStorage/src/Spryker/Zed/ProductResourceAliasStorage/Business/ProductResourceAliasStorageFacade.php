<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\Business\ProductResourceAliasStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface getEntityManager()
 */
class ProductResourceAliasStorageFacade extends AbstractFacade implements ProductResourceAliasStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractStorageSkus(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractStorageWriter()
            ->updateProductAbstractStorageSkus($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteStorageSkus(array $productConcreteIds): void
    {
        $this->getFactory()
            ->createProductConcreteStorageWriter()
            ->updateProductConcreteStorageSkus($productConcreteIds);
    }
}
