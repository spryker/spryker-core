<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageResourceAliasStorageBusinessFactory getFactory()
 */
class ProductImageResourceAliasStorageFacade extends AbstractFacade implements ProductImageResourceAliasStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractImageStorageSkus(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractImageStorageWriter()
            ->updateProductAbstractImageStorageSkus($productAbstractIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteImageStorageSkus(array $productConcreteIds): void
    {
        $this->getFactory()
            ->createProductConcreteImageStorageWriter()
            ->updateProductConcreteImageStorageSkus($productConcreteIds);
    }
}
