<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryStorage\ProductAbstractCategoryStorageWriter;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryStorage\ProductAbstractCategoryStorageWriterInterface;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStorageRepositoryInterface getRepository()
 */
class ProductCategoryResourceAliasStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryStorage\ProductAbstractCategoryStorageWriterInterface
     */
    public function createProductAbstractCategoryStorageWriter(): ProductAbstractCategoryStorageWriterInterface
    {
        return new ProductAbstractCategoryStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
