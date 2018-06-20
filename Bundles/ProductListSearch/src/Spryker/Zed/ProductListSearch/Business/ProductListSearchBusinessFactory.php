<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductListSearch\Business\ProductAbstract\ProductAbstractReader;
use Spryker\Zed\ProductListSearch\Business\ProductAbstract\ProductAbstractReaderInterface;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToLocaleFacadeInterface;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductCategoryFacadeInterface;
use Spryker\Zed\ProductListSearch\ProductListSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductListSearch\Business\ProductAbstract\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getRepository(),
            $this->getProductCategoryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductListSearchToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductListSearchToProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }
}
