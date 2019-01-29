<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductListSearchFacadeInterface;
use Spryker\Zed\ProductList\ProductListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 */
class ProductListCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductListSearchFacadeInterface
     */
    public function getProductListSearchFacade(): ProductListToProductListSearchFacadeInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::FACADE_PRODUCT_LIST_SEARCH);
    }
}
