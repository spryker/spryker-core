<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductListSearch\Business\Expander\ProductConcretePageSearchExpander;
use Spryker\Zed\ProductListSearch\Business\Expander\ProductConcretePageSearchExpanderInterface;
use Spryker\Zed\ProductListSearch\Business\ProductAbstract\ProductAbstractReader;
use Spryker\Zed\ProductListSearch\Business\ProductAbstract\ProductAbstractReaderInterface;
use Spryker\Zed\ProductListSearch\Business\ProductList\ProductDataToProductListMapTransferMapper;
use Spryker\Zed\ProductListSearch\Business\ProductList\ProductDataToProductListMapTransferMapperInterface;
use Spryker\Zed\ProductListSearch\Business\ProductPage\ProductPageDataExpander;
use Spryker\Zed\ProductListSearch\Business\ProductPage\ProductPageDataExpanderInterface;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface;
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
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Business\ProductList\ProductDataToProductListMapTransferMapperInterface
     */
    public function createProductDataToProductListMapTransferMapper(): ProductDataToProductListMapTransferMapperInterface
    {
        return new ProductDataToProductListMapTransferMapper();
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListSearchToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Business\Expander\ProductConcretePageSearchExpanderInterface
     */
    public function createProductConcretePageSearchExpander(): ProductConcretePageSearchExpanderInterface
    {
        return new ProductConcretePageSearchExpander($this->getProductListFacade());
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Business\ProductPage\ProductPageDataExpanderInterface
     */
    public function createProductPageDataExpander(): ProductPageDataExpanderInterface
    {
        return new ProductPageDataExpander(
            $this->getProductListFacade()
        );
    }
}
