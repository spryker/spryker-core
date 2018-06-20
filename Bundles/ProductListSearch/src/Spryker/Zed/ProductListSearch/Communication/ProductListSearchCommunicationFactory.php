<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductPageSearchFacadeInterface;
use Spryker\Zed\ProductListSearch\ProductListSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductListSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductPageSearchFacadeInterface
     */
    public function getProductPageSearchFacade(): ProductListSearchToProductPageSearchFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListSearchToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ProductListSearchDependencyProvider::FACADE_PRODUCT_LIST);
    }
}
