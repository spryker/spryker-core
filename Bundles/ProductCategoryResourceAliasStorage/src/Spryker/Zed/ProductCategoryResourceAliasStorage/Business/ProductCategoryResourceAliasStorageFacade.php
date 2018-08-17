<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryResourceAliasStorageBusinessFactory getFactory()
 */
class ProductCategoryResourceAliasStorageFacade extends AbstractFacade implements ProductCategoryResourceAliasStorageFacadeInterface
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
    public function updateProductAbstractCategoryStorageSkus(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractCategoryStorageWriter()
            ->updateProductAbstractCategoryStorageSkus($productAbstractIds);
    }
}
