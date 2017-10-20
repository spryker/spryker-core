<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryFilterGui\Communication\Table\CategoryRootNodeTable;
use Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiConfig getConfig()
 */
class ProductCategoryFilterGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterInterface
     */
    public function getProductCategoryFilterFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_PRODUCT_CATEGORY_FILTER);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @param int $idLocale
     *
     * @return \Spryker\Zed\ProductCategoryFilterGui\Communication\Table\CategoryRootNodeTable
     */
    public function createCategoryRootNodeTable($idLocale)
    {
        return new CategoryRootNodeTable($this->getQueryContainer(), $idLocale);
    }
}
