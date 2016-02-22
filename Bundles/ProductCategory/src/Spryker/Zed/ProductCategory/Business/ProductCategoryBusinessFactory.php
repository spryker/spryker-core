<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer getQueryContainer()
 */
class ProductCategoryBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductCategory\Business\ProductCategoryManagerInterface
     */
    public function createProductCategoryManager()
    {
        return new ProductCategoryManager(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getCategoryFacade(),
            $this->getTouchFacade(),
            $this->getCmsFacade(),
            $this->getProvidedDependency(ProductCategoryDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface
     */
    protected function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * TODO: https://spryker.atlassian.net/browse/CD-540
     *
     * @return \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCmsInterface
     */
    protected function getCmsFacade()
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::FACADE_CMS);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Business\TransferGeneratorInterface
     */
    public function createProductCategoryTransferGenerator()
    {
        return new TransferGenerator();
    }

}
